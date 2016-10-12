<?php
    LibManager::import('Smarty.Smarty_Compiler\.class');
    class ViewCompiler extends Smarty_Compiler
    {
        function ViewCompiler()
        {
            parent::Smarty_Compiler();
            
            $this->_func_regexp = '\w*:?\w*';
        }
        
        function _compile_compiler_tag($tag_command, $tag_args, &$output)
        {
            if (ereg(':', $tag_command))
            {
                $expl = explode(':', $tag_command);
                $name = $expl[1];
                
                switch($expl[0])
                {
                    case 'compiler':
                        if (!is_callable('smarty_compiler_control'))
                        {
                            LibManager::import('Smarty.plugins.compiler\.control');
                        }

                        if (!Barakus::import(Config::get("plugins_dir_real") . ".View." . $name, true))
                        {
                            return false;
                        }
                        $this->_plugins['compiler'][$tag_command] = array('smarty_compiler_control', null, null, null, true);
                        $tag_args .= ' __class="'.$name.'"';
                        break;
                    default: 
                        return false;
                }
            }
            return parent::_compile_compiler_tag($tag_command, $tag_args, $output);
        }                 
        
        function _compile_custom_tag($tag_command, $tag_args, $tag_modifier, &$output)
        {
            if (ereg(':', $tag_command))
            {
                $expl = explode(':', $tag_command);
                $name = $expl[1];
                
                switch($expl[0])
                {
                    case 'function':
                        if (!is_callable('smarty_function_control'))
                        {
                            LibManager::import('Smarty.plugins.function\.control');
                        }

                        if (!Barakus::import(Config::get("plugins_dir_real") . ".View." . $name, true))
                        {
                            return false;
                        }
                        $this->_plugins['function'][$tag_command] = array('smarty_function_control', null, null, null, true);
                        
                        $tag_args .= ' __class="'.$name.'"';
                        
                        break;         
                    default: 
                        return false;
                }
            }
            return parent::_compile_custom_tag($tag_command, $tag_args, $tag_modifier, $output);
        }                 
        
        function __toString()
        {
            return "ViewCompiler(Object)";
        }
    }
?>
