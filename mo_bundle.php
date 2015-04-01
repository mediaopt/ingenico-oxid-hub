<?php

$rev = exec('git describe --always');

$appIdDate = date('ymd');

function getDir($type)
{
  switch ($type)
  {
    case 'bundles':
      return __DIR__ . '/bundles';
      break;
    case 'tmp':
      return __DIR__ . '/bundles/tmp';
      break;
    case 'src':
      return __DIR__ . '/src';
    case 'docs':
      return __DIR__ . '/static-branding-files';
      break;
  }
}

function clearTmp()
{
  exec('rm -rf ' . getDir('tmp') . '/*');
}

function exportSrc()
{
  exec('cp -R ' . getDir('src') . '/* ' . getDir('tmp'));
}

function zipTmpContents($brand, $suffix, $rev)
{
  exec('cd ' . getDir('tmp') . ' && \
    zip -r ' . getDir('bundles') . '/' . $brand . $suffix . '_r' . $rev . '.zip .');
  echo "\033[1;32mcreated: " . $brand . $suffix . "_r" . $rev . ".zip \n\033[0m";

}

function copyBrandFilesToTmp($brand)
{
  exec('cp -R ' . getDir('bundles') . '/mo_' . $brand . '/* ' . getDir('tmp'));
}

function copyStaticFiles($brand, $dir)
{
  exec('cp -R ' . getDir('docs') . '/' . $brand . '/* ' . $dir);
}

function setRevisionNumber($brand, $rev)
{
  $metaFile = getDir('tmp') . '/copy_this/modules/mo/mo_' . $brand . '/metadata.php';
  $content  = file_get_contents($metaFile);
  $content  = str_replace('##revision##', 'revision ' . $rev, $content);
  file_put_contents($metaFile, $content);
}

function setAppId($brand, $appIdPrefix, $appIdDate)
{
  $configFile = getDir('tmp') . '/copy_this/modules/mo/mo_' . $brand . '/config.php';
  $content    = file_get_contents($configFile);
  $content    = str_replace('##appid##', $appIdPrefix . $appIdDate, $content);
  file_put_contents($configFile, $content);
}

exec('rm ' . getDir('bundles') . '/*.zip');
clearTmp();
exportSrc();
setRevisionNumber('ogone', $rev);
setAppId('ogone', 'OGOX', $appIdDate);
copyStaticFiles('ogone', getDir('tmp'));
zipTmpContents('OGOX' . $appIdDate, 'v30', $rev);
clearTmp();

include __DIR__ . '/mo_branding.php';

foreach ($config['brands'] as $brand => $data)
{
  copyBrandFilesToTmp($brand);
  setRevisionNumber($brand, $rev);
  setAppId($brand, $data['app_id_prefix'], $appIdDate);
  copyStaticFiles($brand, getDir('tmp'));
  zipTmpContents($data['app_id_prefix'] . $appIdDate, 'v30', $rev);
  clearTmp();

  //delete bundle-dir
  exec('rm -rf ' . getBundleDir($brand));
}