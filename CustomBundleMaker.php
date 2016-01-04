<?php

class CustomBundleMaker extends BundleMaker
{

    private $brandConfig = array(
        'moduleName' => 'ogone',
        'values2beReplaced' => array(
            'url' => 'secure.ogone.com',
            'hostedtokentesturl' => 'https://ogone.test.v-psp.com',
            'app_id' => 'MOOGOXDv30',
            'name_normal' => 'Ogone',
            'name_lower_short' => 'ogone__',
            'name_lower' => 'ogone',
            'name_upper' => 'OGONE',
        ),
        'brands' => array(
            'payengine' => array(
                'replacements' => array(
                    'url' => 'secure.payengine.de',
                    'hostedtokentesturl' => 'https://payengine.test.v-psp.com',
                    'app_id' => 'MOPEOXD30',
                    'name_normal' => 'PayEngine',
                    'name_lower_short' => 'payen__',
                    'name_lower' => 'payengine',
                    'name_upper' => 'PAYENGINE'
                ),
                'app_id_prefix' => 'CCOX',
            ),
            'paytool' => array(
                'replacements' => array(
                    'url' => 'secure.paytool.de',
                    'hostedtokentesturl' => 'https://paytool.test.v-psp.com',
                    'app_id' => 'MOPTOXD30',
                    'name_normal' => 'PayTool',
                    'name_lower_short' => 'ptool__',
                    'name_lower' => 'paytool',
                    'name_upper' => 'PAYTOOL'
                ),
                'app_id_prefix' => 'ACOX',
            ),
        ),
        'skipList' => array(
            'directories' => array('.svn'),
            'files' => array(),
        )
    );
    private $currentBrand = null;

    function doEnterDir($dirname)
    {

        if ($dirname == '.' || $dirname == '..') {
            return false;
        }

        //skip directories
        if (in_array($dirname, $this->brandConfig['skipList']['directories'])) {
            return false;
        }

        return true;
    }

    function getFiles($path)
    {
        $files = array();

        $dh = opendir($path);

        while (($file = readdir($dh)) !== false) {
            if (!is_dir($path . '/' . $file)) {
                $files[] = $path . '/' . $file;
            } elseif ($this->doEnterDir($file)) {
                $files = array_merge($this->getFiles($path . '/' . $file), $files);
            }
        }
        closedir($dh);

        return $files;
    }

    function getBundleDir($brand)
    {
        return __DIR__ . '/bundles/mo_' . $brand;
    }

    function copyFiles($files, $sourceBaseDir, $brand)
    {
        $strLength = strlen($sourceBaseDir . '/');
        $bundleDir = $this->getBundleDir($brand);

        $newName = $this->brandConfig['brands'][$brand]['replacements']['name_lower_short'];

        foreach ($files as $file) {
//build targetPath
            $targetName = substr($file, $strLength); //remove base-dir from targetName
            $targetName = str_replace('ogone', $brand, $targetName); //rename folder
            $targetName = str_replace('Ogone', $this->brandConfig['brands'][$brand]['replacements']['name_normal'], $targetName); //rename folder
            $targetName = str_replace($brand . '__', $newName, $targetName); //rename files
            $targetPath = $bundleDir . '/' . $targetName;

//create subdir
            $this->createSubDirsForFile($targetPath);

            copy($file, $targetPath);
        }
    }

    function createSubDirsForFile($filePath)
    {
        $subDir = preg_replace('#^(.+)?/[^/]+$#', '$1', $filePath);

        if (!is_dir($subDir)) {
            mkdir($subDir, 0777, true);
        }
    }

    function brandFiles($brand, $brandConfig)
    {

        foreach ($this->getFiles($this->getBundleDir($brand)) as $file) {
            $content = file_get_contents($file);

            foreach ($this->brandConfig['values2beReplaced'] as $replaceParam => $replaceValue) {
                $content = str_replace($replaceValue, $brandConfig['replacements'][$replaceParam], $content);
            }

            file_put_contents($file, $content);
        }
    }

    function getClassNameFromFilepath($filepath)
    {
        return preg_replace('#^.+?/([^/]+)\.php$#', '$1', $filepath);
    }

    public function createBundle()
    {
        try {
            $this->clearBundles();
            $this->runComposer();
            $this->clearTmp();
            $this->exportSrc();
            $this->setMetadataInformation();
            $this->currentBrand = 'OGOX';
            $this->setAppId('OGOX');
            $this->copyBrandFiles('ogone', $this->config->getTemporaryFolder());
            $this->zipTmpContents();
            $this->clearTmp();

            $files = $this->getFiles($this->config->getSourceCodeFolder());
            foreach ($this->brandConfig['brands'] as $brand => $brandConfig) {
                $this->copyFiles($files, $this->config->getSourceCodeFolder(), $brand);
                $this->brandFiles($brand, $brandConfig);
            }

            foreach ($this->brandConfig['brands'] as $brand => $data) {
                $this->copyBrandFilesToTmp($brand);

                $this->exportSrc();
                $this->setMetadataInformation();
                $this->setAppId($data['app_id_prefix']);
                $this->currentBrand = $data['app_id_prefix'];
                $this->copyBrandFiles($brand, $this->config->getTemporaryFolder());
                $this->zipTmpContents();
                $this->clearTmp();

                //delete bundle-dir
                exec('rm -rf ' . $this->getBundleDir($brand));
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        return 0;
    }

    function copyBrandFiles($brand, $dir)
    {
        exec('cp -R ' . $this->getBrandFolder() . '/' . $brand . '/* ' . $dir);
    }

    protected function copyBrandFilesToTmp($brand)
    {
        exec('cp -R ' . $this->getDir('bundles') . '/mo_' . $brand . '/* ' . $this->getDir('tmp'));
    }

    /**
     * only for Oxid
     */
    function setAppId($appIdPrefix)
    {
        $appIdDate = date('ymd');
        $this->replacePatternInFile($this->getPathToModule() . '/config.php', '##appid##', $appIdPrefix . $appIdDate);
    }

    protected function getBrandFolder()
    {
        return $this->baseDir . '/static-branding-files';
    }

    /**
     * get name for new bundle file
     * @return string
     */
    protected function getFileName()
    {
        return $this->config->getArchivePrefix() . $this->currentBrand
                . $this->config->getArchiveSuffix() . "_v" . $this->config->getVersionNumber()
                . "_rev" . $this->config->getRevisionNumber() . '.zip';
    }

}
