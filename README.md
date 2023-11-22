# Magento AgeVerification by Vuduc (Magentiz)
> Magentiz_AgeVerification Extension, age verification for magento 2.

> Magento 2 extension for verify age.

> Build for https://www.elfbar.de/
![elfbar](https://raw.githubusercontent.com/vdvuong/age-verification/master/docs/popup-elfbar.png)

![image](https://raw.githubusercontent.com/vdvuong/age-verification/master/docs/age-validate-elfbar.png)

## Requirements
  * Magento Community Edition 2.x or Magento Enterprise Edition 2.x.
  * Exec function needs to be enabled in PHP settings.

## Installation Method 1 - Installing via composer
  * Open command line.
  * Using command "cd" navigate to your magento2 root directory.
  * Run command: composer require magentiz/age-verification.

## Installation Method 2 - Installing using archive
  * Download [ZIP Archive](https://github.com/vdvuong/AgeVerification/archive/refs/heads/master.zip).
  * Extract files.
  * In your Magento 2 root directory create folder app/code/Magentiz/AgeVerfication.
  * Copy files and folders from archive to that folder.
  * In command line, using "cd", navigate to your Magento 2 root directory.
  * Run commands:
```
php bin/magento module:enable Magentiz_AgeVerification
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## User guide

Log into the Magento administration panel, go to ```Store > Configuration > Magentiz > Age Verification```.

### In Pop-Up settings, choose Yes to enable age verification pop-up.
![image](https://raw.githubusercontent.com/vdvuong/age-verification/master/docs/popup-config.png)

![image](https://raw.githubusercontent.com/vdvuong/age-verification/master/docs/popup.png)

### In Checkout settings, choose Yes to enable age verification validate.
![image](https://raw.githubusercontent.com/vdvuong/age-verification/master/docs/age-config.png)

![image](https://raw.githubusercontent.com/vdvuong/age-verification/master/docs/age-validate.png)

![image](https://raw.githubusercontent.com/vdvuong/age-verification/master/docs/age-validate-1.png)

## Support
If you have any issues, please [contact me](mailto:vuongvd.se@gmail.com)

## License
The code is licensed under [Open Software License ("OSL") v. 3.0](http://opensource.org/licenses/osl-3.0.php).
