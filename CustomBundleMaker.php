<?php

class CustomBundleMaker extends BundleMaker
{

    function copyBrandFiles($brand, $dir)
    {
        exec('cp -R ' . $this->getBrandFolder() . '/' . $brand . '/* ' . $dir);
    }

    protected function copyBrandFilesToTmp($brand)
    {
        exec('cp -R ' . getDir('bundles') . '/mo_' . $brand . '/* ' . getDir('tmp'));
    }

    /**
     * only for Oxid
     */
    function setAppId($brand, $appIdPrefix, $appIdDate)
    {
        $appIdDate = date('ymd');
        $this->replacePatternInFile($this->getPathToModule() . '/config.php', '##appid##', $appIdPrefix . $appIdDate);
    }
    protected function getBrandFolder()
    {
        return $this->baseDir . '/static-branding-files';
    }
}
