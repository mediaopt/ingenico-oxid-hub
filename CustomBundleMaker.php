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
        return new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
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

    function getBrandDir($brand)
    {
        return $this->config->getBundlesFolder() . '/mo_' . $brand;
    }

    /**
     * copy files from temp folder to brand folder and rename the files and folders according to the brand config
     * @param type $brand
     */
    function copyFilesToBrandFolder($brand)
    {
        $files = $this->getFiles($this->config->getTemporaryFolder());
        $strLength = strlen($this->config->getTemporaryFolder() . '/');
        $bundleDir = $this->getBrandDir($brand);

        $newName = $this->brandConfig['brands'][$brand]['replacements']['name_lower_short'];

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }
//build targetPath
            $targetName = substr($file->getPathname(), $strLength); //remove base-dir from targetName
            $targetName = str_replace('ogone', $brand, $targetName); //rename folder
            $targetName = str_replace('Ogone', $this->brandConfig['brands'][$brand]['replacements']['name_normal'], $targetName); //rename folder
            $targetName = str_replace($brand . '__', $newName, $targetName); //rename files
            $targetPath = $bundleDir . '/' . $targetName;

//create subdir
            $this->createSubDirsForFile($targetPath);

            copy($file->getPathname(), $targetPath);
        }
    }

    /**
     * create SubDirectories if they did not exist yet
     * @param type $filePath
     */
    function createSubDirsForFile($filePath)
    {
        $subDir = preg_replace('#^(.+)?/[^/]+$#', '$1', $filePath);

        if (!is_dir($subDir)) {
            mkdir($subDir, 0777, true);
        }
    }

    /**
     * Replace special names in all files to match the new branding. Replacements come from brandConfig
     * @param type $brand
     * @param type $brandConfig
     */
    function brandFiles($brand, $brandConfig)
    {

        foreach ($this->getFiles($this->getBrandDir($brand)) as $file) {
            if ($file->isDir()) {
                continue;
            }
            $content = file_get_contents($file->getPathname());

            foreach ($this->brandConfig['values2beReplaced'] as $replaceParam => $replaceValue) {
                $content = str_replace($replaceValue, $brandConfig['replacements'][$replaceParam], $content);
            }

            file_put_contents($file->getPathname(), $content);
        }
    }

    /**
     * Special bundle creation script including brandings for concardis and accaptance
     * @return int
     */
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

            // prepare branded files
            foreach ($this->brandConfig['brands'] as $brand => $brandConfig) {
                $this->setAppId($brandConfig['app_id_prefix']);
                $this->copyFilesToBrandFolder($brand);
                $this->brandFiles($brand, $brandConfig);
            }
            $this->clearTmp();
            // package brand files
            foreach ($this->brandConfig['brands'] as $brand => $data) {
                $this->copyBrandFilesToTmp($brand);
                $this->currentBrand = $data['app_id_prefix'];
                $this->copyBrandFiles($brand, $this->config->getTemporaryFolder());
                $this->zipTmpContents();
                $this->clearTmp();

                //delete bundle-dir
                exec('rm -rf ' . $this->getBrandDir($brand));
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
        exec('cp -R ' . $this->config->getBundlesFolder() . '/mo_' . $brand . '/* ' . $this->config->getTemporaryFolder());
    }

    /**
     * only for Oxid
     */
    function setAppId($appIdPrefix)
    {
        $appIdDate = date('ymd');
        $this->replacePatternInFile($this->config->getPathToModule() . '/config.php', '##appid##', $appIdPrefix . $appIdDate);
    }

    protected function getBrandFolder()
    {
        return $this->config->getBaseDir() . '/static-branding-files';
    }

    /**
     * get name for new bundle file using current brand name
     * @return string
     */
    protected function getFileName()
    {
        return $this->config->getArchivePrefix() . $this->currentBrand
                . $this->config->getArchiveSuffix() . "_v" . $this->config->getVersionNumber()
                . "_rev" . $this->config->getRevisionNumber() . '.zip';
    }

}
