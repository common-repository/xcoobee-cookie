=== XcooBee GDPR Cookie Manager ===
Contributors: xcoobee
Tags: xcoobee, privacy, cookie, gdpr, ccpa, security
Requires at least: 4.4.0
Tested up to: 5.3.2
Stable tag: 1.3.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Easy and transparent CCPA and GDPR (EU E-Directive) cookie life-cycle consent management for your site and all your plugins with built in support for fingerprint consent and CCPA Do-Not-Sell option.

== Description ==

Most current cookie CCPA and GDPR notices for WordPress are just that: Overlays that display information but do not actively manage cookie creation and life-cycles. Your site is still responsible for handling cookies, fingerprinting and Do-Not-Sell consent correctly. In contrast to this, the XcooBee Cookie Plugin is a true cookie-consent and life-cycle manager. It will help you implement the premises of the CCPA advent GDPR (European e-directive) correctly.

In addition, the XcooBee Cookie Plugin makes it easy for other plugins to manage their cookies and scripts correctly by simply setting html based tags for the cookies and scripts they need to have user consent to load. This way you will be compliant and continue to use your plugins.

The XcooBee Cookie Plugin Add-on does most of its work by establishing an integration to the open source [Xcoobee Cookie Kit (XCK)](https://github.com/XcooBee/xcoobee-cookie-kit).  

Extensive details on how the XcooBee Cookie Kit operates can be found on our [documentation pages](https://www.xcoobee.com/docs/developer-documentation/plugins/xcoobee-cookie-kit/).

The objective of the XcooBee Cookie Kit (XCK) and this plug-in is to enable websites to manage their Cookie, Fingerprint and Do-Not-Sell consent more effectively and with less annoyance to their users. The XcooBee Cookie Plugin can work in concert with the XcooBee network to improve overall management for users and businesses but that is not required. A subscription also allows override in the visual style and allows you to add your own company branding. However, the XcooBee Cookie Plugin does work independently of XcooBee network.


With this plugin you can:
    - use XcooBee pre-classification of cookies to present simplified and clear details of how you will use cookies
    - share your cookie policies
    - share your terms of service
    - share your privacy policy
    - determine cookie setter/unsetter scripts
    - obtain and manage fingerprint consent
    - allow users to manage consent (remove/add consent)  
    - trigger removal scripts when needed
    - set timing and position of information display
    - display notification in different languages
    - display Do-Not-Sell option needed for CCPA
    - manage 3rd party scripts such as Google Analytics cookie creation

With subscription to XcooBee you can also:    
    - document your consent interactions
    - remote manage user consent
    - document proper interactions and response time to user requests
    - set additional style and company branding
    - use crowd cookie intelligence 
    - auto manage CCPA Do-Not-Sell expiration
    - obtain smart consent, pre-negotiated with XcooBee network
    - transparently determine whether notification is necessary depending on country of origin
    - get user sentiment (their attitude about your site and services)


The XcooBee Cookie Plug-in is an Add-on. It requires the [XcooBee For WordPress](https://wordpress.org/plugins/xcoobee/) plugin to work correctly. You will need to install it first.

= Tutorial for Programmers and Plugin Developers =

[youtube https://youtu.be/gKYNoARNXRo]


== How can other Plugins Work with XcooBee Cookie Add-on ==

If you are a plugin developer who needs to use cookies or load scripts based on user consent, there is no need to develop your own. You can simply connect to the XcooBee infrastructure for cookies and everything will be automatically managed for you.

Using PHP:

= setting cookies =

After you install the add-on you can set managed cookies in PHP using the exposed XcooBee special function `xbee_cookie()`.

function header `function xbee_cookie($action, $category, array $cookie)` 


Example use:

```
To set:
xbee_cookie('set', 'application', ['name' => 'cookieName', 'value' => 'cookieValue', 'days' => 365]);

to remove:
xbee_cookie('unset', 'application', ['name' => 'cookieName']);

```

When setting a cookie, the `days` are optional.

= intercepting PHP calls =

As an option the XcooBee Cookie plugin allows you to catch all non-managed calls to create cookies for your site and assign them to a consent category and manage them.
There is nothing you need to do except turn on the feature. This ensures compliance even when you have rogue plugins setting cookies you are not aware of through PHP.



Using HTML:

You can generate special HTML tags that the XcooBee Cookie Plugin will be able to use to determine your cookies and scripts automatically. The Cookie plugin will then set or remove based on user consent:

= cookie tag =

You can use `<xbee-cookie>` tag to declare a cookie anywhere in the HTML DOM. The addition here is the category. Please classify according to XcooBee classification system: application, usage, statistics, or advertising

Example of two tags one declaring a necessary (application) cookie while the other is declaring a personalization (usage) cookie:

```
<xbee-cookie category="application" name="nameofcookie">the_value_of_the_cookie</xbee-cookie>
<xbee-cookie category="usage" name="theme">dark-blue</xbee-cookie>
```

= script tag =

You can use the `<xbee-script>` tag to declare a script to be loaded later after proper consent has been obtained. You should also consider an equivalent removal or cleanup script to be run should the consent be removed.

Example of google analytics script managed by XcooBee:

```
  <!-- Google Analytics example -->
  <xbee-script category="statistics" action="set">
      
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      
      ga('create', 'UA-XXXXX-Y', 'auto');
      ga('send', 'pageview');
      
  </xbee-script>
  <!-- End Google Analytics -->

```


== XcooBee Cookie Kit (XCK) Details ==


The XCK forms the base of the XcooBee Cookie Plugin for WordPress and makes it easy to enable cookie consent on your website. It handles all user interaction to obtain consent and lets the website know which cookies and scripts can be used for each users while they visit your website. Similarly, your website can now be informed when users change their consent even when they are not visiting it.

The XCK is not just an information overlay. It is an active cookie and consent manager for your site.

The XCK is one of the most transparent and frictionless ways to manage cookies on your site. It will not pop-up, in, or otherwise hinder the user experience when it is not needed. Yet, at the same time, it provides full compliance with European e-directives and related GDPR rules.

Website owners can easily react to data-requests and report on privacy related requests if needed via the XcooBee network.

The XCK does not require a XcooBee connection to work for your website. You will still have access to the majority of user consent gathering but will not have central insight and consent management.

The XCK is responsive and will adjust easily to different screens including mobile uses.

== About XcooBee ==

XcooBee is a privacy-focused data exchange network with a mission to protect the digital rights and privacy of consumers and businesses alike.

XcooBee offers a number of plugins and add-ons for users to pick and choose the tools they need to improve the privacy and GDPR compliance.

[XcooBee For WordPress](https://wordpress.org/plugins/xcoobee/) is our common plugin that you need to use with our other XcooBee WordPress add-ons. To get the most of the plugins and add-ons we recommend you obtain an API key. This can be obtained freely on the [XcooBee network](https://www.xcoobee.com) by upgrading to a developer account.

[youtube https://www.youtube.com/watch?v=4JBoTWU2Apc]


= Why XcooBee? =

XcooBee is the only network that tries to rewrite how individuals and companies interact. We enable end users to manage their consent while allowing companies fair use.

This is not only a good vision, but with the advent of the CCPA, GDPR, and similar laws many processes need better support.

Small and medium businesses do not have time to make tools or manage complex software. XcooBee aims to simplify this and make powerful tools available to WordPress sites as well as their customers. Most of these are available for free.

= How XcooBee works? =

We at XcooBee believe that privacy is not static and not the same for everybody.

We deliver tools, services, and techniques to allow individuals to control the exchange, distribution and management of their own information while allowing businesses fair use and compliance.

Tools we provide remove the complexity of compliance with CCPA and GDPR when using WordPress. All this while improving the convenience and trust of the end-customers. A paid subscription to XcooBee is optional but recommended if you wish to use all the features.

= Built with developers in mind =

We support XcooBee and all its add-ons with comprehensive, easily-accessible documentation. With our docs, you’ll learn how to easily use and even extend our plugin.

= Add-ons =

WordPress.org is home to some amazing extensions for this plugin, including:

- [XcooBee For WordPress](https://wordpress.org/plugins/xcoobee/) - this is the base plugin required by all add-ons
- [XcooBee Cookie](https://wordpress.org/plugins/xcoobee-cookie/)
- [XcooBee Document](https://wordpress.org/plugins/xcoobee-document/)
- [XcooBee Data Consent](https://wordpress.org/plugins/xcoobee-forms/)
- [XcooBee Subject Access Request](https://wordpress.org/plugins/xcoobee-sar/)

== Installation ==

= Minimum Requirements =

* PHP version 5.6.0 or greater (PHP 7.2 or greater is recommended)

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of XcooBee, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “XcooBee for WordPress” and click Search Plugins. Once you’ve found our plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your webserver via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Frequently Asked Questions ==

= Where can I find documentation for XcooBee? =

- [XcooBee Concepts](https://www.xcoobee.com/docs/xcoobee-concepts)
- [XcooBee User Levels](https://www.xcoobee.com/docs/xcoobee-user-levels/)
- [Developer Documentation](https://www.xcoobee.com/docs/developer-documentation)
- [Bee Documentation](https://www.xcoobee.com/docs/bee-documentation)
- [XcooBee Terms of Service](https://www.xcoobee.com/about/terms-of-service/)
- [XcooBee Privacy Policy](https://www.xcoobee.com/about/privacy/)


= Where can I get support or talk to other users? =

If you need any help with XcooBee, please use our [contact us](https://www.xcoobee.com/contactus) page or via the Feedback button in XcooBee application to get in touch with us.

== Screenshots ==

1. The cookie settings panel.
2. Cookie kit options.
3. Script loading options.
4. Cookie popup collapsed.
5. Cookie popup expanded.

== Changelog ==

See CHANGELOG file in project