<?php

$baseDir = __DIR__ . '/src'; //add parameter if bundle / branding is needed for full package (e.g. certification)

$config = array(
    'moduleName'        => 'ogone',
    'values2beReplaced' => array(
        'url'              => 'secure.ogone.com',
        'hostedtokentesturl' => 'https://ogone.test.v-psp.com',
        'app_id'           => 'MOOGOXDv30',
        'name_normal'      => 'Ogone',
        'name_lower_short' => 'ogone__',
        'name_lower'       => 'ogone',
        'name_upper'       => 'OGONE',
    ),
    'brands'           => array(
        'payengine' => array(
            'replacements' => array(
                'url'              => 'secure.payengine.de',
                'hostedtokentesturl' => 'https://payengine.test.v-psp.com',
                'app_id'           => 'MOPEOXD30',
                'name_normal'      => 'PayEngine',
                'name_lower_short' => 'payen__',
                'name_lower'       => 'payengine',
                'name_upper'       => 'PAYENGINE'
            ),
            'app_id_prefix'    => 'CCOX',
        ),
        'paytool'          => array(
            'replacements' => array(
                'url'              => 'secure.paytool.de',
                'hostedtokentesturl' => 'https://paytool.test.v-psp.com',
                'app_id'           => 'MOPTOXD30',
                'name_normal'      => 'PayTool',
                'name_lower_short' => 'ptool__',
                'name_lower'       => 'paytool',
                'name_upper'       => 'PAYTOOL'
            ),
            'app_id_prefix'    => 'ACOX',
        ),
        'igenico'          => array(
            'replacements' => array(
                'url'              => 'secure.ogone.de',
                'hostedtokentesturl' => 'https://ogone.test.v-psp.com',
                'app_id'           => 'MOOGOXDv30',
                'name_normal'      => 'Ingenico',
                'name_lower_short' => 'ingenico__',
                'name_lower'       => 'ingenico',
                'name_upper'       => 'INGENICO'
            ),
            'app_id_prefix'    => 'OGOX',
        ),
    ),
    'skipList'         => array(
        'directories' => array('.svn'),
        'files' => array(),
    )
);

function doEnterDir($dirname)
{
  global $config;

  if ($dirname == '.' || $dirname == '..')
  {
    return false;
  }

  //skip directories
  if (in_array($dirname, $config['skipList']['directories']))
  {
    return false;
  }

  return true;
}

function getFiles($path)
{
  $files = array();

  $dh = opendir($path);

  while (($file = readdir($dh)) !== false)
  {
    if (!is_dir($path . '/' . $file))
    {
      $files[] = $path . '/' . $file;
    }
    elseif (doEnterDir($file))
    {
      $files = array_merge(getFiles($path . '/' . $file), $files);
    }
  }
  closedir($dh);

  return $files;
}

function getBundleDir($brand)
{
  return __DIR__ . '/bundles/mo_' . $brand;
}

function copyFiles($files, $sourceBaseDir, $brand, $config)
{
  $strLength = strlen($sourceBaseDir . '/');
  $bundleDir = getBundleDir($brand);

  $newName = $config['brands'][$brand]['replacements']['name_lower_short'];

  foreach ($files as $file)
  {
    //build targetPath
    $targetName = substr($file, $strLength); //remove base-dir from targetName
    $targetName = str_replace('ogone', $brand, $targetName); //rename folder
    $targetName = str_replace('Ogone', $config['brands'][$brand]['replacements']['name_normal'], $targetName); //rename folder
    $targetName = str_replace($brand . '__', $newName, $targetName); //rename files
    $targetPath = $bundleDir . '/' . $targetName;

    //create subdir
    createSubDirsForFile($targetPath);

    copy($file, $targetPath);
  }
}

function createSubDirsForFile($filePath)
{
  $subDir = preg_replace('#^(.+)?/[^/]+$#', '$1', $filePath);

  if (!is_dir($subDir))
  {
    mkdir($subDir, 0777, true);
  }
}

function brandFiles($brand, $brandConfig)
{
  global $config;

  foreach (getFiles(getBundleDir($brand)) as $file)
  {
    $content = file_get_contents($file);

    foreach ($config['values2beReplaced'] as $replaceParam => $replaceValue)
    {
      $content = str_replace($replaceValue, $brandConfig['replacements'][$replaceParam], $content);
    }

    file_put_contents($file, $content);
  }
}

function getClassNameFromFilepath($filepath)
{
  return preg_replace('#^.+?/([^/]+)\.php$#', '$1', $filepath);
}

$files = getFiles($baseDir);

foreach ($config['brands'] as $brand => $brandConfig)
{
  copyFiles($files, $baseDir, $brand, $config);
  brandFiles($brand, $brandConfig);
}