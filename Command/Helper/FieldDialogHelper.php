<?php
/*
 * This file is part of the WSGeneratorBundle package.
 *
 * (c) Benjamin Georgeault <https://github.com/WedgeSama/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WS\GeneratorBundle\Command\Helper;

use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Container;

class FieldDialogHelper extends DialogHelper {

    protected static $VALIDATORS = array(
            'NotBlank' => array(
                    'types' => array(
                            'string', 
                            'text' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Blank' => array(
                    'types' => array(
                            'string', 
                            'text' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'NotNull' => array(
                    'types' => array(
                            'all' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Null' => array(
                    'types' => array(
                            'all' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'True' => array(
                    'types' => array(
                            'boolean' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'False' => array(
                    'types' => array(
                            'boolean' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Type' => array(
                    'types' => array(
                            'string', 
                            'text', 
                            'boolean', 
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float', 
                            'array', 
                            'json_array', 
                            'object' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Email' => array(
                    'types' => array(
                            'string' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Length' => array(
                    'types' => array(
                            'string', 
                            'text' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Url' => array(
                    'types' => array(
                            'string' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Regex' => array(
                    'types' => array(
                            'string' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Ip' => array(
                    'types' => array(
                            'string' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Range' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'EqualTo' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'NotEqualTo' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'NotIdenticalTo' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float' 
                    ) 
            ), 
            'LessThan' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'LessThanOrEqual' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'GreaterThan' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'GreaterThanOrEqual' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'float' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Date' => array(
                    'types' => array(
                            'date' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'DateTime' => array(
                    'types' => array(
                            'date', 
                            'time', 
                            'datetime' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Time' => array(
                    'types' => array(
                            'time' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Choice' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint', 
                            'decimal', 
                            'string' 
                    ), 
                    'opts' => array(
                            'choices' => 'array', 
                            'groups' => 'array' 
                    ) 
            ), 
            'Collection' => array(
                    'types' => array(
                            'array', 
                            'json_array', 
                            'object' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Count' => array(
                    'types' => array(
                            'array', 
                            'json_array', 
                            'object' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'CardScheme' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint' 
                    ), 
                    'opts' => array(
                            'schemes' => 'array', 
                            'groups' => 'array' 
                    ) 
            ), 
            'Luhn' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Iban' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Isbn' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ), 
            'Issn' => array(
                    'types' => array(
                            'integer', 
                            'smallint', 
                            'bigint' 
                    ), 
                    'opts' => array(
                            'groups' => 'array' 
                    ) 
            ) 
    );

    protected static $ALLOWED_TYPES = array(
            'string', 
            'text', 
            'date', 
            'time', 
            'datetime', 
            'boolean', 
            'integer', 
            'smallint', 
            'bigint', 
            'decimal', 
            'float', 
            'array', 
            'json_array', 
            'object' 
    );

    protected static $TYPE_OPTIONS = array(
            'length' => array(
                    'type' => 'numeric', 
                    'default' => 255, 
                    'on' => array(
                            'string' 
                    ) 
            ), 
            'precision' => array(
                    'type' => 'numeric', 
                    'default' => 10, 
                    'on' => array(
                            'decimal' 
                    ) 
            ), 
            'scale' => array(
                    'type' => 'numeric', 
                    'default' => 2, 
                    'on' => array(
                            'decimal' 
                    ) 
            ), 
            'nullable' => array(
                    'type' => 'bool', 
                    'default' => 'false', 
                    'on' => array(
                            'all' 
                    ) 
            ), 
            'unique' => array(
                    'type' => 'bool', 
                    'default' => 'false', 
                    'on' => array(
                            'all' 
                    ) 
            ) 
    );

    protected static $ALLOWED_LINKS = array(
            'ManyToOne' => array(
                    'opts' => array(
                            'JoinColumn', 
                            'inversedBy', 
                            'cascade', 
                            'Valid' 
                    ) 
            ), 
            'ManyToMany' => array(
                    'opts' => array(
                            'OrderBy', 
                            'JoinTable', 
                            'inversedBy', 
                            'mappedBy', 
                            'cascade', 
                            'Valid' 
                    ) 
            ), 
            'OneToOne' => array(
                    'opts' => array(
                            'JoinColumn', 
                            'inversedBy', 
                            'mappedBy', 
                            'cascade', 
                            'Valid' 
                    ) 
            ), 
            'OneToMany' => array(
                    'opts' => array(
                            'OrderBy', 
                            'JoinTable', 
                            'mappedBy', 
                            'Valid' 
                    ) 
            ) 
    );

    protected static $LINK_OPTIONS = array(
            'OrderBy' => array(
                    'type' => 'string' 
            ), 
            'JoinColumn' => array(
                    'opts' => array(
                            'name' => '<DEFAULT>', 
                            'refCol' => 'id', 
                            'unique' => 'true', 
                            'nullable' => 'false', 
                    ) 
            ), 
            'Valid', 
            'cascade' => array(
                    'type' => 'array', 
                    'values' => array(
                            'all', 
                            'persist', 
                            'remove', 
                            'merge', 
                            'detach' 
                    ) 
            ), 
            'mappedBy' => array(
                    'type' => 'string' 
            ), 
            'inversedBy' => array(
                    'type' => 'string' 
            ), 
            'JoinTable' => array(
                    'opts' => array(
                            'name' => '<DEFAULT>', 
                            'refCol' => 'id', 
                            'invName' => '<DEFAULT>', 
                            'invRefCol' => 'id' 
                    ) 
            ) 
    );

    public function makeFieldAsArray($name, $type, $type_opts = null, 
        $validate_rules = null) {
        $field = array(
                'columnName' => $name, 
                'fieldName' => lcfirst(Container::camelize($name)), 
                'type' => $type 
        );
        
        if ($type_opts != null)
            $field['type_opts'] = $type_opts;
        
        if ($validate_rules != null)
            $field['validate_rules'] = $validate_rules;
        
        return $field;
    }

    public function makeIdField() {
        $id = $this->makeFieldAsArray('id', 'integer');
        $id['id'] = true;
        
        return $id;
    }

    public function askFieldName($output, $fieldsList = array()) {
        $fieldValidator = function ($field) use($fieldsList) {
            if (! preg_match('#^[a-zA-Z][/a-zA-Z0-9_]*$#', $field) &&
                     $field != "")
                throw new \InvalidArgumentException('Nom de champ invalide.');
            
            foreach ($fieldsList as $exist)
                if ($exist['columnName'] == $field)
                    throw new \InvalidArgumentException('Champ deja existant.');
            
            return $field;
        };
        
        return $this->askVar($output, 'Nom du nouveau champ', $fieldValidator);
    }

    public function askFieldType($output, $fieldName = null) {
        $types = self::$ALLOWED_TYPES;
        $validator = function ($type) use($types) {
            if (! in_array($type, $types))
                throw new \InvalidArgumentException('Type inexistant.');
            
            return $type;
        };
        
        $defaultType = 'string';
        if (substr($fieldName, - 3) == '_at') {
            $defaultType = 'datetime';
        } elseif (substr($fieldName, - 3) == '_id') {
            $defaultType = 'integer';
        } elseif (substr($fieldName, 0, 3) == 'is_' ||
                 substr($fieldName, 0, 4) == 'has_') {
            $defaultType = 'boolean';
        } elseif (substr($fieldName, - 1) == 's') {
            $defaultType = 'array';
        } elseif ($fieldName == 'text') {
            $defaultType = 'text';
        }
        
        return $this->askVar($output, ' - Type de champ', $validator, 
                $defaultType);
    }

    public function askFieldTypeOptions($output, $type) {
        $opts = array();
        foreach (self::$TYPE_OPTIONS as $opt => $params) {
            if (in_array($type, $params['on']) || in_array('all', $params['on'])) {
                $opt_type = $params['type'];
                $validator = function ($val) use($opt_type) {
                    
                    $bool = function ($string) {
                        switch ($string) {
                            case ("true") :
                                return true;
                                break;
                            case ("false") :
                                return false;
                                break;
                            default :
                                return $string;
                        }
                    };
                    
                    $methode = 'is_' . $opt_type;
                    
                    if ($opt_type == "bool")
                        $val = $bool($val);
                    
                    if (! $methode($val))
                        throw new \InvalidArgumentException('Mauvaise valeur.');
                    
                    return $val;
                };
                
                $opts[$opt] = $this->askVar($output, ' - Option ' . $opt, 
                        $validator, $params['default']);
            }
        }
        
        return $opts;
    }

    public function askValidatorName($output, $type, $validator_rules) {
        $VALIDATORS = self::$VALIDATORS;
        $validator = function ($name) use($type, $validator_rules, $VALIDATORS) {
            if ($name == "")
                return $name;
            
            if (! isset($VALIDATORS[$name]))
                throw new \InvalidArgumentException('Regle inconnue.');
            
            if (isset($validator_rules[$name]))
                throw new \InvalidArgumentException(
                        'Regle "' . $name . '"  deja existante.');
            
            if (! in_array($type, $VALIDATORS[$name]['types']))
                throw new \InvalidArgumentException(
                        'Regle non autorisee pour le type "' . $type . '".');
            
            return $name;
        };
        
        return $this->askVar($output, ' - Regle', $validator);
    }

    public function askValidatorOptName($output, $valid_name, $valid_opts) {
        $validator = function ($name) use($valid_name, $valid_opts) {
            if ($name == "")
                return $name;
            
            if (isset($valid_opts[$name]))
                throw new \InvalidArgumentException(
                        'Option "' . $name . '"  deja existante.');
            
            $reflector = new \ReflectionClass(
                    '\\Symfony\\Component\\Validator\\Constraints\\' .
                             $valid_name);
            
            if (! $reflector->hasProperty($name))
                throw new \InvalidArgumentException('Option inconnue.');
            
            return $name;
        };
        
        return $this->askVar($output, '   - Option', $validator);
    }

    public function askFieldValidatorOpts($output, $valid_name) {
        $valid_opts = array();
        
        $reflector = new \ReflectionClass(
                '\\Symfony\\Component\\Validator\\Constraints\\' . $valid_name);
        
        $args = $reflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        $print_args = array();
        foreach ($args as $arg)
            $print_args[] = $arg->name;
        
        $output->writeln('OPTIONS : ' . implode(', ', $print_args));
        
        while (true) {
            // nom de l'options
            $opt_name = $this->askValidatorOptName($output, 
                    $valid_name, $valid_opts);
            
            if (! $opt_name) {
                break;
            }
            
            // type de l'option
            if (isset(
                    self::$VALIDATORS[$valid_name]['opts'][$opt_name])) {
                if (self::$VALIDATORS[$valid_name]['opts'][$opt_name] == "array") {
                    $opt_value = array();
                    $output->writeln('     - Values');
                    
                    while (true) {
                        $opt_value_one = $this->askVar($output, '       -', 
                                function ($var) {
                                    return $var;
                                });
                        if (! $opt_value_one)
                            break;
                        $opt_value[] = $opt_value_one;
                    }
                }
            } else {
                $opt_value = $this->askVar($output, '     - Value', 
                        function ($var) {
                            return $var;
                        });
            }
            
            $name_keyp_value = 'value';
            if (is_array($opt_value))
                $name_keyp_value = 'values';
            
            $valid_opts[$opt_name] = array(
                    'name' => $opt_name, 
                    $name_keyp_value => $opt_value 
            );
        }
        
        return $valid_opts;
    }

    public function askFieldValidators($output, $type) {
        $validate_rules = null;
        if ($this->askConfirmation($output, 
                ' - Ajouter des regles de validation ? [yes]', true)) {
            $validate_rules = array();
            
            $valid_names = array();
            foreach (self::$VALIDATORS as $key => $value)
                if (in_array($type, $value['types']))
                    $valid_names[] = $key;
            $output->writeln(
                    array(
                            '', 
                            '		Regles de validation :', 
                            ' ' . implode(', ', $valid_names), 
                            '		Mot cle values : <DEFAULT>' 
                    ));
            
            while (true) {
                $output->writeln('');
                // nom du validator
                $valid_name = $this->askValidatorName($output, 
                        $type, $validate_rules);
                
                if (! $valid_name) {
                    break;
                }
                
                // les options
                $valid_opts = $this->askFieldValidatorOpts(
                        $output, $valid_name);
                
                $validate_rules[$valid_name] = array(
                        'name' => $valid_name, 
                        'opts' => $valid_opts 
                );
            }
        }
        
        return $validate_rules;
    }

    public function askLinkType($output, $fieldName = null) {
        $types = array();
        foreach (self::$ALLOWED_LINKS as $key => $value)
            $types[] = $key;
        
        $validator = function ($type) use($types) {
            if (! in_array($type, $types))
                throw new \InvalidArgumentException('Type inexistant.');
            
            return $type;
        };
        
        $defaultType = 'ManyToOne';
        if (substr($fieldName, - 1) == 's')
            $defaultType = 'OneToMany';
        
        return $this->askVar($output, ' - Type de lien', $validator, 
                $defaultType);
    }

    public function askLinkTypeOpt($output, $type, $type_opts = array()) {
        $link_opts = self::$ALLOWED_LINKS[$type]['opts'];
        
        $validator = function ($opt) use($link_opts, $type_opts) {
            if ($opt == "")
                return $opt;
            
            if (isset($type_opts[$opt]))
                throw new \InvalidArgumentException('Option deja existante.');
            
            if (! in_array($opt, $link_opts))
                throw new \InvalidArgumentException('Option non autorisee.');
            
            return $opt;
        };
        
        return $this->askVar($output, '   - Option', $validator);
    }

    public function askLinkTypeOptions($output, $type) {
        $type_opts = array();
        $link_opts = self::$ALLOWED_LINKS[$type]['opts'];
        
        $output->writeln('OPTIONS : ' . implode(', ', $link_opts));
        
        while (true) {
            // nom option
            $opt_name = $this->askLinkTypeOpt($output, $type, 
                    $type_opts);
            
            if (! $opt_name)
                break;
            
            if (isset(self::$LINK_OPTIONS[$opt_name]['type'])) {
                switch (self::$LINK_OPTIONS[$opt_name]['type']) {
                    case 'array' :
                        $opt_value = array();
                        $output->writeln(
                                array(
                                        'ALLOWED VALUES : ' .
                                                 implode(', ', 
                                                        self::$LINK_OPTIONS[$opt_name]['values']), 
                                                '     - Values' 
                                ));
                        
                        $allowed_opts = self::$LINK_OPTIONS[$opt_name]['values'];
                        while (true) {
                            $opt_value_one = $this->askVar($output, '       -', 
                                    function ($var) use($opt_value, 
                                    $allowed_opts) {
                                        if ($var == "")
                                            return $var;
                                        
                                        if (in_array($var, $opt_value))
                                            throw new \InvalidArgumentException(
                                                    'Value deja existante.');
                                        if (! in_array($var, $allowed_opts))
                                            throw new \InvalidArgumentException(
                                                    'Value inconnue.');
                                        
                                        return $var;
                                    });
                            if (! $opt_value_one)
                                break;
                            $opt_value[$opt_value_one] = $opt_value_one;
                        }
                        break;
                    default :
                        $opt_value = $this->askVar($output, '     - Value', 
                                function ($var) {
                                    return $var;
                                });
                }
            } else if (isset(self::$LINK_OPTIONS[$opt_name]['opts'])) {
                $opt_value = array();
                
                $allowed_opts = array();
                
                foreach (self::$LINK_OPTIONS[$opt_name]['opts'] as $key => $value)
                    $allowed_opts[] = $key;
                
                $output->writeln(
                        array(
                                'ALLOWED PARAMETRES : ' .
                                         implode(', ', $allowed_opts), 
                                        '     - Parametres' 
                        ));
                
                $validator = function ($var) use($allowed_opts, $opt_value) {
                    if ($var == "")
                        return $var;
                    
                    if (in_array($var, $opt_value))
                        throw new \InvalidArgumentException(
                                'Parametre deja existant.');
                    
                    if (! in_array($var, $allowed_opts))
                        throw new \InvalidArgumentException('Parametre inconnu.');
                    
                    return $var;
                };
                
                while (true) {
                    $opt_value_one = $this->askVar($output, 
                            '       - Parametre', $validator);
                    if (! $opt_value_one)
                        break;
                    $opt_value_value = $this->askVar($output, '       - Value', 
                            function ($var) {
                                return $var;
                            }, 
                            self::$LINK_OPTIONS[$opt_name]['opts'][$opt_value_one]);
                    
                    $opt_value[$opt_value_one] = array(
                            'name' => $opt_value_one, 
                            'value' => $opt_value_value 
                    );
                }
            } else {
                $opt_value = null;
            }
            
            if (is_array($opt_value)) {
                if (@is_array($opt_value[0]))
                    $name_opt_value = 'optsWithParams';
                else
                    $name_opt_value = 'opts';
            } else
                $name_opt_value = 'value';
            
            $type_opts[$opt_name] = array(
                    'name' => $opt_name, 
                    $name_opt_value => $opt_value 
            );
        }
        
        return $type_opts;
    }

}