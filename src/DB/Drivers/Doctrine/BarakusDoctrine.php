<?php
class BarakusDoctrine
{
    public function generateSomeModelsFromDb($directory, array $tables = array(), array $databases = array(), array $options = array())
    {
        $connections = Doctrine_Manager::getInstance()->getConnections();
        foreach ($connections as $name => $connection) {
          // Limit the databases to the ones specified by $databases.
          // Check only happens if array is not empty
          if ( ! empty($databases) && !in_array($name, $databases)) {
            continue;
          }

          $builder = new Doctrine_Import_BarakusBuilder();
          $builder->setTargetPath($directory);
          $builder->setOptions($options);
          
          $import = new Doctrine_Import_BarakusSchema();
          $import->setOptions($options);
          
          $classes = array();
          
          foreach ($tables as $table) {
              $definition = array();
              $definition['tableName'] = $table;
              $definition['className'] = Doctrine_Inflector::classify($table);
              $definition['columns'] = $connection->import->listTableColumns($table);
              //$definition['connection'] = $name;
              $definition['detect_relations'] = true;
              $definition['no_definition'] = false;
              $definition['is_main_class'] = false;
              
              $definitions[$definition['className']] = $definition;
              
              $classes[] = $definition['className'];
          }
        }
        if ($import)
        {
            $definitions = $import->buildRelationships($definitions);
            foreach ($definitions as $definition)
            {
                $builder->buildRecord($definition);
            }
        }

        return $classes;
    }
}
?>
