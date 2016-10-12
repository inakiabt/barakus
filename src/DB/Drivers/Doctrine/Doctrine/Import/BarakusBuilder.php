<?php
    class Doctrine_Import_BarakusBuilder extends Doctrine_Import_Builder
    {
        /**
         * buildRecord
         *
         * @param array $options 
         * @param array $columns 
         * @param array $relations 
         * @param array $indexes 
         * @param array $attributes 
         * @param array $templates 
         * @param array $actAs 
         * @return void=
         */
        public function buildRecord(array $definition)
        {
            if ( !isset($definition['className'])) {
                throw new Doctrine_Import_Builder_Exception('Missing class name.');
            }
            
            $definition['topLevelClassName'] = $definition['className'];
            
            if ($this->generateBaseClasses()) {
                $definition['is_package'] = (isset($definition['package']) && $definition['package']) ? true:false;
                
                if ($definition['is_package']) {
                    $e = explode('.', $definition['package']);
                    $definition['package_name'] = $e[0];
                    unset($e[0]);
                    
                    $definition['package_path'] = ! empty($e) ? implode(DIRECTORY_SEPARATOR, $e):$definition['package_name'];
                }
                
                // Top level definition that extends from all the others
                $topLevel = $definition;
                unset($topLevel['tableName']);
                
                // If we have a package then we need to make this extend the package definition and not the base definition
                // The package definition will then extends the base definition
                $topLevel['inheritance']['extends'] = (isset($topLevel['package']) && $topLevel['package']) ? $this->_packagesPrefix . $topLevel['className']:'Base' . $topLevel['className'];
                $topLevel['no_definition'] = true;
                $topLevel['generate_once'] = true;
                $topLevel['is_main_class'] = true;
                unset($topLevel['connection']);

                // Package level definition that extends from the base definition
                if (isset($definition['package'])) {
                    
                    $packageLevel = $definition;
                    $packageLevel['className'] = $topLevel['inheritance']['extends'];
                    $packageLevel['inheritance']['extends'] = 'Base' . $topLevel['className'];
                    $packageLevel['no_definition'] = true;
                    $packageLevel['abstract'] = true;
                    $packageLevel['override_parent'] = true;
                    $packageLevel['generate_once'] = true;
                    $packageLevel['is_package_class'] = true;
                    unset($packageLevel['connection']);
                    
                    $packageLevel['tableClassName'] = $packageLevel['className'] . 'Table';
                    $packageLevel['inheritance']['tableExtends'] = isset($definition['inheritance']['extends']) ? $definition['inheritance']['extends'] . 'Table':'Doctrine_Table';
                    
                    $topLevel['tableClassName'] = $topLevel['topLevelClassName'] . 'Table';
                    $topLevel['inheritance']['tableExtends'] = $packageLevel['className'] . 'Table';
                } else {
                    $topLevel['tableClassName'] = $topLevel['className'] . 'Table';
                    $topLevel['inheritance']['tableExtends'] = isset($definition['inheritance']['extends']) ? $definition['inheritance']['extends'] . 'Table':'Doctrine_Table';
                }

                $baseClass = $definition;
                $baseClass['className'] = 'Base' . $baseClass['className'];
                $baseClass['abstract'] = true;
                $baseClass['override_parent'] = false;
                $baseClass['is_base_class'] = true;

                $this->writeDefinition($baseClass);
                
                if ( ! empty($packageLevel)) {
                    $this->writeDefinition($packageLevel);
                }
                
                $this->writeDefinition($topLevel);
            } else {
                $this->writeDefinition($definition);
            }
        }
        
        
        /**
         * writeDefinition
         *
         * @param array $options 
         * @param array $columns 
         * @param array $relations 
         * @param array $indexes 
         * @param array $attributes 
         * @param array $templates 
         * @param array $actAs 
         * @return void
         */
        public function writeDefinition(array $definition)
        {
            $definitionCode = $this->buildDefinition($definition);

            $fileName = $definition['className'] . $this->_suffix;

            $packagesPath = $this->_packagesPath ? $this->_packagesPath:$this->_path;

            // If this is a main class that either extends from Base or Package class
            if (isset($definition['is_main_class']) && $definition['is_main_class']) {
                // If is package then we need to put it in a package subfolder
                if (isset($definition['is_package']) && $definition['is_package']) {
                    $writePath = $this->_path . DIRECTORY_SEPARATOR . $definition['package_name'];
                // Otherwise lets just put it in the root of the path
                } else {
                    $writePath = $this->_path;
                }

                $this->writeTableDefinition($definition['tableClassName'], $writePath, array('extends' => $definition['inheritance']['tableExtends']));
            }
            // If is the package class then we need to make the path to the complete package
            else if (isset($definition['is_package_class']) && $definition['is_package_class']) {
                $writePath = $packagesPath . DIRECTORY_SEPARATOR . $definition['package_path'];

                $this->writeTableDefinition($definition['tableClassName'], $writePath, array('extends' => $definition['inheritance']['tableExtends']));
            }
            // If it is the base class of the doctrine record definition
            else if (isset($definition['is_base_class']) && $definition['is_base_class']) {
                // If it is a part of a package then we need to put it in a package subfolder
                if (isset($definition['is_package']) && $definition['is_package']) {
                    $basePath = $this->_path . DIRECTORY_SEPARATOR . $definition['package_name'];
                    $writePath = $basePath . DIRECTORY_SEPARATOR . $this->_baseClassesDirectory;
                // Otherwise lets just put it in the root generated folder
                } else {
                    $writePath = $this->_baseClassesDirectory;
                }
            }

            // If we have a writePath from the if else conditionals above then use it
            if (isset($writePath)) {
                Doctrine_Lib::makeDirectories($writePath);

                $writePath .= DIRECTORY_SEPARATOR . $fileName;
            // Otherwise none of the conditions were met and we aren't generating base classes
            } else {
                Doctrine_Lib::makeDirectories($this->_path);

                $writePath = $this->_path . DIRECTORY_SEPARATOR . $fileName;
            }

            $code = "<?php" . PHP_EOL;

            if (isset($definition['connection']) && $definition['connection']) {
                $code .= "// Connection Component Binding\n";
                $code .= "Doctrine_Manager::getInstance()->bindComponent('" . preg_replace('|^(Base)|', '', $definition['className']) . "', '" . $definition['connection'] . "');\n";
            }

            $code .= PHP_EOL . $definitionCode;
                         
            if (isset($definition['generate_once']) && $definition['generate_once'] === true) {
                if ( ! file_exists($writePath)) {
                    $bytes = file_put_contents($writePath, $code);
                }
            } else {
                $bytes = file_put_contents($writePath, $code);
            }

            if (isset($bytes) && $bytes === false) {
                throw new Doctrine_Import_Builder_Exception("Couldn't write file " . $writePath);
            } 
        }
        
    }
?>
