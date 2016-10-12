<?php
    class Doctrine_Import_BarakusSchema extends Doctrine_Import_Schema
    {
        public function buildRelationships(array $definition)
        {
            return $this->_buildRelationships($definition);
        }
    }
?>
