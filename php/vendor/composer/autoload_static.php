<?php    
namespace Composer\Autoload;

class ComposerStaticInitf4fdae3150d0328870952fba376358d4
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'setasign\\Fpdi\\' => 14,
        ),
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'setasign\\Fpdi\\' => 
        array (
            0 => __DIR__ . '/..' . '/setasign/fpdi/src',
        ),
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'FPDF' => __DIR__ . '/..' . '/setasign/fpdf/fpdf.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf4fdae3150d0328870952fba376358d4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf4fdae3150d0328870952fba376358d4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf4fdae3150d0328870952fba376358d4::$classMap;

        }, null, ClassLoader::class);
    }
}
