<?php
    class PersistentActiveRecord extends ADODB_Active_Record
    {
        function save($data = array())
        {
            if (!is_array($data))
            {
                Error::ArrayExpected();
            }
            $this->__fieldsMap($data);
            parent::Save();
        }
        
        function __fieldsMap($data)
        {
            $fields = $this->GetAttributeNames();

            foreach ($fields as $field)
            {
                if (isset($data[$field]))
                {
                    $this->$field = $data[$field];
                }
            }
        }
    }  
?>
