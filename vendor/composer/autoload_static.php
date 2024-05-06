<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4bfa755ad204a2f10b057f94fa964aec
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lengow\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lengow\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Lengow' => __DIR__ . '/../..' . '/lengow.php',
        'LengowAction' => __DIR__ . '/../..' . '/classes/models/LengowAction.php',
        'LengowAddress' => __DIR__ . '/../..' . '/classes/models/LengowAddress.php',
        'LengowBackup' => __DIR__ . '/../..' . '/classes/models/LengowBackup.php',
        'LengowCarrier' => __DIR__ . '/../..' . '/classes/models/LengowCarrier.php',
        'LengowCart' => __DIR__ . '/../..' . '/classes/models/LengowCart.php',
        'LengowCatalog' => __DIR__ . '/../..' . '/classes/models/LengowCatalog.php',
        'LengowConfiguration' => __DIR__ . '/../..' . '/classes/models/LengowConfiguration.php',
        'LengowConfigurationForm' => __DIR__ . '/../..' . '/classes/models/LengowConfigurationForm.php',
        'LengowConnector' => __DIR__ . '/../..' . '/classes/models/LengowConnector.php',
        'LengowController' => __DIR__ . '/../..' . '/classes/controllers/LengowController.php',
        'LengowCountry' => __DIR__ . '/../..' . '/classes/models/LengowCountry.php',
        'LengowCustomer' => __DIR__ . '/../..' . '/classes/models/LengowCustomer.php',
        'LengowDashboardController' => __DIR__ . '/../..' . '/classes/controllers/LengowDashboardController.php',
        'LengowException' => __DIR__ . '/../..' . '/classes/models/LengowException.php',
        'LengowExport' => __DIR__ . '/../..' . '/classes/models/LengowExport.php',
        'LengowFeed' => __DIR__ . '/../..' . '/classes/models/LengowFeed.php',
        'LengowFeedController' => __DIR__ . '/../..' . '/classes/controllers/LengowFeedController.php',
        'LengowFile' => __DIR__ . '/../..' . '/classes/models/LengowFile.php',
        'LengowGender' => __DIR__ . '/../..' . '/classes/models/LengowGender.php',
        'LengowHelpController' => __DIR__ . '/../..' . '/classes/controllers/LengowHelpController.php',
        'LengowHomeController' => __DIR__ . '/../..' . '/classes/controllers/LengowHomeController.php',
        'LengowHook' => __DIR__ . '/../..' . '/classes/models/LengowHook.php',
        'LengowImport' => __DIR__ . '/../..' . '/classes/models/LengowImport.php',
        'LengowImportOrder' => __DIR__ . '/../..' . '/classes/models/LengowImportOrder.php',
        'LengowInstall' => __DIR__ . '/../..' . '/classes/models/LengowInstall.php',
        'LengowLegalsController' => __DIR__ . '/../..' . '/classes/controllers/LengowLegalsController.php',
        'LengowLink' => __DIR__ . '/../..' . '/classes/models/LengowLink.php',
        'LengowList' => __DIR__ . '/../..' . '/classes/models/LengowList.php',
        'LengowLog' => __DIR__ . '/../..' . '/classes/models/LengowLog.php',
        'LengowMain' => __DIR__ . '/../..' . '/classes/models/LengowMain.php',
        'LengowMainSettingController' => __DIR__ . '/../..' . '/classes/controllers/LengowMainSettingController.php',
        'LengowMarketplace' => __DIR__ . '/../..' . '/classes/models/LengowMarketplace.php',
        'LengowMethod' => __DIR__ . '/../..' . '/classes/models/LengowMethod.php',
        'LengowNameParser' => __DIR__ . '/../..' . '/classes/models/LengowNameParser.php',
        'LengowOrder' => __DIR__ . '/../..' . '/classes/models/LengowOrder.php',
        'LengowOrderCarrier' => __DIR__ . '/../..' . '/classes/models/LengowOrderCarrier.php',
        'LengowOrderController' => __DIR__ . '/../..' . '/classes/controllers/LengowOrderController.php',
        'LengowOrderDetail' => __DIR__ . '/../..' . '/classes/models/LengowOrderDetail.php',
        'LengowOrderError' => __DIR__ . '/../..' . '/classes/models/LengowOrderError.php',
        'LengowOrderLine' => __DIR__ . '/../..' . '/classes/models/LengowOrderLine.php',
        'LengowOrderSettingController' => __DIR__ . '/../..' . '/classes/controllers/LengowOrderSettingController.php',
        'LengowPaymentModule' => __DIR__ . '/../..' . '/classes/models/LengowPaymentModule.php',
        'LengowProduct' => __DIR__ . '/../..' . '/classes/models/LengowProduct.php',
        'LengowShop' => __DIR__ . '/../..' . '/classes/models/LengowShop.php',
        'LengowSync' => __DIR__ . '/../..' . '/classes/models/LengowSync.php',
        'LengowToolbox' => __DIR__ . '/../..' . '/classes/models/LengowToolbox.php',
        'LengowToolboxController' => __DIR__ . '/../..' . '/classes/controllers/LengowToolboxController.php',
        'LengowToolboxElement' => __DIR__ . '/../..' . '/classes/models/LengowToolboxElement.php',
        'LengowTranslation' => __DIR__ . '/../..' . '/classes/models/LengowTranslation.php',
        'PrestaShop\\Module\\Lengow\\Controller\\AdminOrderController' => __DIR__ . '/../..' . '/src/Controller/AdminOrderController.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4bfa755ad204a2f10b057f94fa964aec::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4bfa755ad204a2f10b057f94fa964aec::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4bfa755ad204a2f10b057f94fa964aec::$classMap;

        }, null, ClassLoader::class);
    }
}