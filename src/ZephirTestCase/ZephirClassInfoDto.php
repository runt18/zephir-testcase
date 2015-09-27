<?php

namespace ZephirTestCase;

class ZephirClassInfoDto
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $namespace;
    /**
     * @var string
     */
    private $filePath;

    /**
     * @param string $value
     */
    public function setClassName($value)
    {
        $this->className = $value;
    }
    
    /**
     * @param string $value
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
    }
    
    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
    
    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    
    /**
     * @return string
     */
    public function getFilePath()
    {
        return strtolower(str_replace('\\', '/', $this->namespace) . '/' . $this->className);
    }
    
    /**
     * @return string
     */
    public function getBaseNamespace()
    {
        return strpos($this->namespace, '\\') ? strstr($this->namespace, '\\') : $this->namespace;
    }
    
    public function getBaseDir()
    {
        return strtolower($this->getBaseNamespace());
    }
    
    public function getExtensionName()
    {
        return $this->getBaseDir();
    }
}