# PHP Instagram

The goal of this project is to provide a PHP class for implementing Instragram on websites with an e-mail notification system when the authentication token is no longer working.

## Getting Started

To get started, simply clone or download the files and then do the work. 

### Prerequisites

Make sure you have a website in place to do this.

```
<link rel='stylesheet' id='instagram'  href='/css/instagram.css' type='text/css' />

<?
include('inc/instagram.php');
$kinsta = new fpinstagram('8634556440.1677ed0.2b93091e63ed41e59a9c890120ce559c');
$kinsta->setAdminEmail('aaron@firstpagemarketing.com');
$kinsta->setDomainEmail('email@hostdomain.ca');
$kinsta->setHolderClass('howl-carousel');
if ($kinsta->connect()) {
    echo $kinsta->display();
} 
?>
```

### Installing

1. Simply upload the instagram.php file into /_inc/ or /inc/ whatever your setup.
2. Edit the instagram.scss under /css/ or /_css/ file and compile, then upload to the corresponding css folder in your server
3. Include the instagram.php file

```
include('inc/instagram.php');
```

4. Create an instance of the fpinstagram class using the authentication token.

```
$kinsta = new fpinstagram('8634556440.1677ed0.2b93091e63ed41e59a9c890120ce559c');
```

5. Set the e-mail addresses for both the admin to receive notifications and the domain which will appear in the form

```
$kinsta->setAdminEmail('aaron@firstpagemarketing.com');
$kinsta->setDomainEmail('email@hostdomain.ca');
```

6. Optional set a holder class which can be used with Owl Carousel

```
$kinsta->setHolderClass('howl-carousel');
```

7. Call the connect method to ensure the code can connect to the Instagram API. Then run it if everything works.

```
if ($kinsta->connect()) {
    echo $kinsta->display();
} 
```

8. The output will spit out an unordered list of images, descriptions in HTML code

9. If you are using the OWL implementation, please ensure to include the following in the footer of your website.

```
$kinsta->footerScripts();

```



## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [FirstPage Awesomeness](http://www.firstpagemarketing.com) - Best company ever
* [Maven](https://maven.apache.org/) - Dependency Management
* [ROME](https://rometools.github.io/rome/) - Used to generate RSS Feeds

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Aaron Smith** - *Initial work* - [FirstPage](https://github.com/FirstPage)

See also the list of [contributors](https://github.com/php-instagram/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration for all
* etc
