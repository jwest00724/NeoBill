-- Database: `solidstate`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `account`
-- 

CREATE TABLE `account` (
  `id` int(11) NOT NULL auto_increment,
  `type` enum('Individual Account','Business Account') NOT NULL default 'Business Account',
  `status` enum('Active','Inactive','Pending') NOT NULL default 'Active',
  `billingstatus` enum('Bill','Do Not Bill') NOT NULL default 'Bill',
  `billingday` int(11) NOT NULL default '0',
  `businessname` varchar(255) default NULL,
  `contactname` varchar(255) NOT NULL default '',
  `contactemail` varchar(255) NOT NULL default '',
  `address1` varchar(255) default NULL,
  `address2` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `state` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `postalcode` varchar(255) default NULL,
  `phone` varchar(255) default NULL,
  `mobilephone` varchar(255) default NULL,
  `fax` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `domainservice`
-- 

CREATE TABLE `domainservice` (
  `tld` varchar(255) NOT NULL default '',
  `modulename` varchar(255) default NULL,
  `description` blob,
  `price1yr` decimal(10,2) NOT NULL default '0.00',
  `price2yr` decimal(10,2) NOT NULL default '0.00',
  `price3yr` decimal(10,2) NOT NULL default '0.00',
  `price4yr` decimal(10,2) NOT NULL default '0.00',
  `price5yr` decimal(10,2) NOT NULL default '0.00',
  `price6yr` decimal(10,2) NOT NULL default '0.00',
  `price7yr` decimal(10,2) NOT NULL default '0.00',
  `price8yr` decimal(10,2) NOT NULL default '0.00',
  `price9yr` decimal(10,2) NOT NULL default '0.00',
  `price10yr` decimal(10,2) NOT NULL default '0.00',
  `taxable` enum('Yes','No') NOT NULL default 'No',
  PRIMARY KEY  (`tld`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `domainservicepurchase`
-- 

CREATE TABLE `domainservicepurchase` (
  `id` int(11) NOT NULL auto_increment,
  `accountid` int(11) NOT NULL default '0',
  `tld` varchar(255) NOT NULL default '',
  `term` enum('1 year','2 year','3 year','4 year','5 year','6 year','7 year','8 year','9 year','10 year') NOT NULL default '1 year',
  `domainname` varchar(255) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `expiredate` datetime NOT NULL default '0000-00-00 00:00:00',
  `accountname` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `hostingservice`
-- 

CREATE TABLE `hostingservice` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `description` blob,
  `setupprice1mo` decimal(10,2) NOT NULL default '0.00',
  `price1mo` decimal(10,2) NOT NULL default '0.00',
  `setupprice3mo` decimal(10,2) NOT NULL default '0.00',
  `price3mo` decimal(10,2) NOT NULL default '0.00',
  `setupprice6mo` decimal(10,2) NOT NULL default '0.00',
  `price6mo` decimal(10,2) NOT NULL default '0.00',
  `setupprice12mo` decimal(10,2) NOT NULL default '0.00',
  `price12mo` decimal(10,2) NOT NULL default '0.00',
  `uniqueip` enum('Required','Not Required') NOT NULL default 'Not Required',
  `taxable` enum('Yes','No') NOT NULL default 'No',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `hostingservicepurchase`
-- 

CREATE TABLE `hostingservicepurchase` (
  `id` int(11) NOT NULL auto_increment,
  `accountid` int(11) NOT NULL default '0',
  `hostingserviceid` int(11) NOT NULL default '0',
  `serverid` int(11) default NULL,
  `term` enum('1 month','3 month','6 month','12 month') NOT NULL default '1 month',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `invoice`
-- 

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL auto_increment,
  `accountid` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `periodbegin` datetime NOT NULL default '0000-00-00 00:00:00',
  `periodend` datetime NOT NULL default '0000-00-00 00:00:00',
  `note` blob,
  `terms` int(11) NOT NULL default '1',
  `outstanding` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `invoiceitem`
-- 

CREATE TABLE `invoiceitem` (
  `id` int(11) NOT NULL auto_increment,
  `invoiceid` int(11) NOT NULL default '0',
  `quantity` int(11) default NULL,
  `unitamount` decimal(10,2) NOT NULL default '0.00',
  `text` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `ipaddress`
-- 

CREATE TABLE `ipaddress` (
  `ipaddress` int(11) NOT NULL default '0',
  `serverid` int(11) NOT NULL default '0',
  `purchaseid` int(11) default NULL,
  PRIMARY KEY  (`ipaddress`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `log`
-- 

CREATE TABLE `log` (
  `id` int(11) NOT NULL auto_increment,
  `type` enum('notice','warning','error','critical','security') NOT NULL default 'notice',
  `module` varchar(255) NOT NULL default '',
  `text` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default '0',
  `remoteip` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=736 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `module`
-- 

CREATE TABLE `module` (
  `name` varchar(255) NOT NULL default '',
  `enabled` enum('Yes','No') NOT NULL default 'No',
  `type` varchar(255) NOT NULL default '',
  `shortdescription` varchar(32) default NULL,
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`name`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `modulesetting`
-- 

CREATE TABLE `modulesetting` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `value` text,
  `modulename` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `note`
-- 

CREATE TABLE `note` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `accountid` int(11) NOT NULL default '0',
  `username` varchar(10) NOT NULL default '',
  `text` blob NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `order`
-- 

CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `businessname` varchar(255) NOT NULL default '',
  `contactname` varchar(255) NOT NULL default '',
  `contactemail` varchar(255) NOT NULL default '',
  `address1` varchar(255) NOT NULL default '',
  `address2` varchar(255) NOT NULL default '',
  `city` varchar(255) NOT NULL default '',
  `state` varchar(255) NOT NULL default '',
  `country` char(3) NOT NULL default '',
  `postalcode` varchar(255) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',
  `mobilephone` varchar(255) default NULL,
  `fax` varchar(255) default NULL,
  `username` varchar(10) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `orderdomain`
-- 

CREATE TABLE `orderdomain` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `orderid` int(10) unsigned NOT NULL default '0',
  `orderitemid` int(10) unsigned NOT NULL default '0',
  `type` enum('New','Transfer','Existing') NOT NULL default 'Existing',
  `tld` varchar(255) default NULL,
  `domainname` varchar(255) NOT NULL default '',
  `term` enum('1 year','2 year','3 year','4 year','5 year','6 year','7 year','8 year','9 year','10 year') default '1 year',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `orderhosting`
-- 

CREATE TABLE `orderhosting` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `orderid` int(10) unsigned NOT NULL default '0',
  `orderitemid` int(10) unsigned NOT NULL default '0',
  `serviceid` int(10) unsigned NOT NULL default '0',
  `term` enum('1 month','3 months','6 months','12 months') NOT NULL default '1 month',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `payment`
-- 

CREATE TABLE `payment` (
  `id` int(11) NOT NULL auto_increment,
  `invoiceid` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `amount` decimal(10,2) NOT NULL default '0.00',
  `transaction1` varchar(255) default NULL,
  `transaction2` varchar(255) default NULL,
  `type` enum('Cash','Check','Credit Card','Paypal','Account Credit','Other') NOT NULL default 'Cash',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `product`
-- 

CREATE TABLE `product` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` blob,
  `price` decimal(10,2) NOT NULL default '0.00',
  `taxable` enum('Yes','No') NOT NULL default 'No',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `productpurchase`
-- 

CREATE TABLE `productpurchase` (
  `id` int(11) NOT NULL auto_increment,
  `productid` int(11) NOT NULL default '0',
  `accountid` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `note` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `server`
-- 

CREATE TABLE `server` (
  `id` int(11) NOT NULL auto_increment,
  `hostname` varchar(255) NOT NULL default '',
  `location` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `settings`
-- 

CREATE TABLE `settings` (
  `setting` varchar(255) NOT NULL default '',
  `value` text,
  PRIMARY KEY  (`setting`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `taxrule`
-- 

CREATE TABLE `taxrule` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `country` char(2) NOT NULL default '',
  `state` varchar(255) default NULL,
  `rate` decimal(3,2) NOT NULL default '0.00',
  `allstates` enum('Yes','Specific') NOT NULL default 'Yes',
  `description` varchar(255) NOT NULL default 'Tax',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `user`
-- 

CREATE TABLE `user` (
  `username` varchar(10) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `type` enum('Account Manager','Administrator') NOT NULL default 'Account Manager',
  `firstname` varchar(30) default NULL,
  `lastname` varchar(30) default NULL,
  `email` varchar(30) default NULL,
  PRIMARY KEY  (`username`)
) TYPE=MyISAM;

-- Admin password is 'temp'
INSERT INTO `user` (`username`, `password`, `type`, `firstname`, `lastname`, `email`) VALUES ('admin', '3d801aa532c1cec3ee82d87a99fdf63f', 'Administrator', 'Default', 'Administrator', '');

INSERT INTO `settings` VALUES ('company_name', 'SolidState v0.4');
INSERT INTO `settings` VALUES ('company_email', 'demo@solid-state.org');
INSERT INTO `settings` VALUES ('welcome_subject', 'Welcome to Web Hosting Company!');
INSERT INTO `settings` VALUES ('welcome_email', 'This is the welcome email that can be sent to your new \r\ncustomers.');
INSERT INTO `settings` VALUES ('nameservers_ns1', 'ns1.example.com');
INSERT INTO `settings` VALUES ('nameservers_ns2', 'ns2.example.com');
INSERT INTO `settings` VALUES ('nameservers_ns3', 'ns3.example.com');
INSERT INTO `settings` VALUES ('nameservers_ns4', 'ns4.example.com');
INSERT INTO `settings` VALUES ('invoice_text', 'Invoice #{invoice_id}\r\n\r\n===================================================================\r\nItem                                    Price     Qty  Total\r\n===================================================================\r\n{invoice_items}===================================================================\r\n\r\nInvoice Total: {invoice_total}\r\nPayments Received: {invoice_payments}\r\nBalance: {invoice_balance}\r\nDate Due: {invoice_due}\r\n\r\nIf you have any questions about this Invoice, please contact\r\nbilling@example.com.  Thank you!\r\n\r\nWeb Hosting Company\r\n');
INSERT INTO `settings` VALUES ('locale_language', 'english');
INSERT INTO `settings` VALUES ('locale_currency_symbol', '$');
INSERT INTO `settings` VALUES ('order_remote_password', '12c500ed0b7879105fb46af0f246be87');

