-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2019 at 01:26 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zendgility`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserInsert`(IN `username` text, IN `UserEmail` text, IN `password` text, IN `plainpassword` text, IN `isactive` boolean, IN `createdby` int, out `output` text)
BEGIN
 DECLARE usercount INT; 
 DECLARE texterr text; 
 
	SELECT	usercount = COUNT(*)
	FROM	umg_tbUser
	WHERE	UserEmail = UserEmail
	AND		IsDeleted	= 0
    AND		IsActive	= 1; 
  /* check in user table */
  IF (usercount > 0) then 
  SET output = 'UserAlreadyExist';
  /*RETURN texterr;*/
  
  SELECT UserCount = Count(username) 
  FROM   oauth_users 
  WHERE  username = username;
  ELSEIF usercount > 0 then 
  SET output = 'UserAlreadyExist';
  /*RETURN texterr;*/
  ELSE
  INSERT INTO umg_tbuser 
              ( 
                          username, 
                          useremail, 
                          plainpassword, 
                          isactive, 
                          isdeleted, 
                          createdby, 
                          createdon, 
                          lastmodifiedby, 
                          lastmodifiedon, 
                          userguid 
              ) 
              /*OUTPUT   inserted.UserID, inserted.EmployeeID, inserted.Username, Inserted.PlainPassword, INSERTED.IsActive, inserted.IsDeleted, inserted.CreatedBy, inserted.CreatedOn, inserted.LastModifiedBy, inserted.LastModifiedOn, INSERTED.UserGUID*/
              VALUES 
              ( 
                          username, 
                          useremail, 
                          plainpassword, 
                          isactive, 
                          0, 
                          createdby, 
                          NOW(), 
                          createdby, 
                          NOW(), 
                          Uuid() 
              ); 
   
  /*now insert into oauth user table*/
  INSERT INTO oauth_users 
              ( 
                          username, 
                          password, 
                          first_name, 
                          last_name 
              ) 
              VALUES 
              ( 
                          username, 
                          password, 
                          username, 
                          username 
              );
              set output = mysql_insert_id();
              END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `userslist`(IN `username` TEXT, IN `password` TEXT, IN `plainpassword` TEXT, IN `isactive` BOOLEAN, IN `createdby` INT)
BEGIN
 DECLARE usercount INT; 
 DECLARE texterr text; 
 
	SELECT	usercount = COUNT(*)
	FROM	umg_tbUser
	WHERE	UserEmail = UserEmail
	AND		IsDeleted	= 0
    AND		IsActive	= 1; 
  /* check in user table */
  IF (usercount > 0) then 
  SET texterr = 'UserAlreadyExist';
  /*RETURN texterr;*/
  
  SELECT UserCount = Count(username) 
  FROM   oauth_users 
  WHERE  username = username;
  ELSEIF usercount > 0 then 
  SET texterr = 'UserAlreadyExist';
  /*RETURN texterr;*/
  ELSE
  INSERT INTO umg_tbuser 
              ( 
                          username, 
                          useremail, 
                          plainpassword, 
                          isactive, 
                          isdeleted, 
                          createdby, 
                          createdon, 
                          lastmodifiedby, 
                          lastmodifiedon, 
                          userguid 
              ) 
              /*OUTPUT   inserted.UserID, inserted.EmployeeID, inserted.Username, Inserted.PlainPassword, INSERTED.IsActive, inserted.IsDeleted, inserted.CreatedBy, inserted.CreatedOn, inserted.LastModifiedBy, inserted.LastModifiedOn, INSERTED.UserGUID*/
              VALUES 
              ( 
                          username, 
                          useremail, 
                          plainpassword, 
                          isactive, 
                          0, 
                          createdby, 
                          Getdate(), 
                          createdby, 
                          Getdate(), 
                          Uuid() 
              ); 
   
  /*now insert into oauth user table*/
  INSERT INTO oauth_users 
              ( 
                          username, 
                          password, 
                          first_name, 
                          last_name 
              ) 
              VALUES 
              ( 
                          username, 
                          password, 
                          username, 
                          username 
              );
              END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `access_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL,
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`access_token`, `client_id`, `user_id`, `expires`, `scope`) VALUES
('0a026d45e020b81c0e1e10c67dbe2c078a66050f', 'testclient', 'testuser', '2019-02-07 12:56:43', NULL),
('0b8b00aa78f7830e20fc6046e1056a9dc742476e', 'testclient', 'testuser', '2019-02-07 10:34:40', NULL),
('0fc912cd62a1982ecc580c03cb6589cecceb8dea', 'testclient', 'testuser', '2019-02-06 08:35:44', NULL),
('109a68e4b834de601d8872cbd5a5823073906372', 'testclient', 'testuser', '2019-01-11 09:12:59', NULL),
('160a4fb5bb8c2df7d3a73dcd18972b830267d970', 'testclient', 'testuser', '2019-02-06 10:01:29', NULL),
('2350e8a5d463e87c8fbfaf20e84f62d70f317ba4', 'testclient', 'testuser', '2019-02-07 11:07:05', NULL),
('4ccbbc26576d971a4f443ebc3d3f810f81c405ac', 'testclient', 'testuser', '2019-01-14 03:21:48', NULL),
('4ce9dab3afc2aa21c811bfe4e7055d471ea18b67', 'testclient', 'testuser', '2019-02-07 09:01:22', NULL),
('629bfe83185bb21c8178db2cca529fded7173600', 'testclient', 'testuser', '2019-01-14 03:02:43', NULL),
('6a3e32fdcd0c18da845e19f1eaafebaaa8b507f5', 'testclient', 'testuser', '2019-02-06 07:00:11', NULL),
('6cd5d7483f57436c5eff23a4f2e777f6c043b600', 'testclient', 'testuser', '2019-02-08 06:16:22', NULL),
('6e7c9d55d8cdec37a8262261020f2e5a00263504', 'testclient', 'testuser', '2019-02-06 09:04:33', NULL),
('7d64d5df80f0a614c4f99ab8610eeb6da55bda54', 'testclient', 'testuser', '2019-02-06 10:05:13', NULL),
('864a8ed7f68a7d61873c864089d577695768d5c6', 'testclient', 'testuser', '2019-02-07 08:33:41', NULL),
('880937a66c75abde09ea132896c59c5c163edbb1', 'testclient', 'testuser', '2019-02-07 08:33:31', NULL),
('8ec538fd9605fb83191239e353c567996c68ce12', 'testclient', 'testuser', '2019-02-08 12:16:50', NULL),
('91dc3a8e6ff7493d1e369afef69f0de6eb40a8df', 'testclient', 'testuser', '2019-02-06 07:04:20', NULL),
('998a8193179c22327c8f69a47c59a285ffdeb153', 'testclient', 'testuser', '2019-02-06 07:03:50', NULL),
('a4a6c48f4da95a640107b216b6b2924f4de33f57', 'testclient', 'testuser', '2019-01-14 03:50:50', NULL),
('b4a5b8411f419b7fd8fe5607d69c72b0eea34775', 'testclient', 'testuser', '2019-02-07 10:27:27', NULL),
('bd397cfef871b061aef1d77a31b0521b474bd340', 'testclient', 'testuser', '2019-02-06 08:34:04', NULL),
('f0dc16513050c0a9c24db1429e5450891b0b5efb', 'testclient', 'testuser', '2019-02-06 06:57:02', NULL),
('fa84848dd17a68f606f9733eb4c7e4cff42e8135', 'testclient', 'testuser', '2019-02-06 07:03:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_authorization_codes`
--

CREATE TABLE IF NOT EXISTS `oauth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `redirect_uri` varchar(2000) DEFAULT NULL,
  `expires` timestamp NOT NULL,
  `scope` varchar(2000) DEFAULT NULL,
  `id_token` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`authorization_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `client_id` varchar(80) NOT NULL,
  `client_secret` varchar(80) NOT NULL,
  `redirect_uri` varchar(2000) NOT NULL,
  `grant_types` varchar(80) DEFAULT NULL,
  `scope` varchar(2000) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`client_id`, `client_secret`, `redirect_uri`, `grant_types`, `scope`, `user_id`) VALUES
('testclient ', '$2y$10$XUH1D2tH9Do0Ua7yy/w/jO/DBLXiOB43utQzHn.ip3tY/X1scsE0q', '', 'password', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_jwt`
--

CREATE TABLE IF NOT EXISTS `oauth_jwt` (
  `client_id` varchar(80) NOT NULL,
  `subject` varchar(80) DEFAULT NULL,
  `public_key` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `refresh_token` varchar(40) NOT NULL,
  `client_id` varchar(80) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `expires` timestamp NOT NULL,
  `scope` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `oauth_refresh_tokens`
--

INSERT INTO `oauth_refresh_tokens` (`refresh_token`, `client_id`, `user_id`, `expires`, `scope`) VALUES
('1573ab48eb43e62d4f385a7e30c2890b72ca626d', 'testclient', 'testuser', '2019-02-21 09:34:40', NULL),
('1ebfdc69d738ff76259eff975cec68a810bf64c3', 'testclient', 'testuser', '2019-02-22 05:16:22', NULL),
('258bff58465710e17967995e017581010061bc1e', 'testclient', 'testuser', '2019-02-20 09:05:13', NULL),
('3021ab786246a7c30a4d62579667c2a79ca578be', 'testclient', 'testuser', '2019-01-28 02:21:48', NULL),
('312f44dd84e054e4c3ec62e10fbd67c02bfd45a5', 'testclient', 'testuser', '2019-02-21 09:27:27', NULL),
('440cc293dcf82e00db53e14e777d27ca81ac12d4', 'testclient', 'testuser', '2019-02-20 06:04:20', NULL),
('5969c4388482edc096aa832db36a129434274936', 'testclient', 'testuser', '2019-02-20 05:57:02', NULL),
('64b0e9e4d0217135487df0d6089261d5a8677b32', 'testclient', 'testuser', '2019-02-20 06:00:11', NULL),
('91a16eade3417b8ec6d2a1c77ea59cfa8455f234', 'testclient', 'testuser', '2019-01-28 02:02:43', NULL),
('92e300dbd1adb84d09e228ada373ea1dcb5065b2', 'testclient', 'testuser', '2019-02-22 11:16:51', NULL),
('996ebe39ee161519e2267627b104f1b2d67f5fac', 'testclient', 'testuser', '2019-02-20 06:03:50', NULL),
('a876ba26dadc96627f70e3be4c93de6c47724989', 'testclient', 'testuser', '2019-01-28 02:50:50', NULL),
('aa514cd133b1667be0b786fa5de99554f0316504', 'testclient', 'testuser', '2019-02-20 06:03:04', NULL),
('ab69c24c7c0b436ac995b026b3245e5038e140ca', 'testclient', 'testuser', '2019-02-20 08:04:33', NULL),
('b3744856daeb3ad38cd6c215af814b37e73aaea9', 'testclient', 'testuser', '2019-02-20 07:34:05', NULL),
('bc9a6608d5b493b6582ca7a63c47fd6b29c0e629', 'testclient', 'testuser', '2019-01-25 08:12:59', NULL),
('cb5a9614671683c731fae0d3720f9826ee3824e8', 'testclient', 'testuser', '2019-02-20 07:35:44', NULL),
('cbc4429c507ec32d7605ae2b96b3357274476ce2', 'testclient', 'testuser', '2019-02-21 07:33:41', NULL),
('cfd6f6c91fb72f2296442998b83c2c91d5af7c7f', 'testclient', 'testuser', '2019-02-21 08:01:22', NULL),
('e64febf38fae1d1acbe9d2acd408d44b919fa7c7', 'testclient', 'testuser', '2019-02-20 09:01:29', NULL),
('e6dfd00d2c8f2b5376bfaaa033a334f3e104e5c9', 'testclient', 'testuser', '2019-02-21 11:56:43', NULL),
('ea646e43073dc2d6357f9ae2ab8bb129bd3bbae1', 'testclient', 'testuser', '2019-02-21 10:07:05', NULL),
('f4691f4398d0e1a4bcc6c44805c7fed2ad34e708', 'testclient', 'testuser', '2019-02-21 07:33:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_scopes`
--

CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `type` varchar(255) NOT NULL DEFAULT 'supported',
  `scope` varchar(2000) DEFAULT NULL,
  `client_id` varchar(80) DEFAULT NULL,
  `is_default` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_users`
--

CREATE TABLE IF NOT EXISTS `oauth_users` (
  `username` varchar(255) NOT NULL,
  `password` varchar(2000) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `oauth_users`
--

INSERT INTO `oauth_users` (`username`, `password`, `first_name`, `last_name`) VALUES
('khurram', 'abc123', 'khurram', 'khurram'),
('testuser', '$2y$10$XUH1D2tH9Do0Ua7yy/w/jO/DBLXiOB43utQzHn.ip3tY/X1scsE0q', 'Muhammad', 'Bilal');

-- --------------------------------------------------------

--
-- Table structure for table `umg_tbuser`
--

CREATE TABLE IF NOT EXISTS `umg_tbuser` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` text NOT NULL,
  `UserEmail` text NOT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `IsDeleted` tinyint(1) NOT NULL,
  `CreatedBy` int(11) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `LastModifiedBy` int(11) NOT NULL,
  `LastModifiedOn` datetime NOT NULL,
  `UserGUID` text NOT NULL,
  `PlainPassword` text NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `umg_tbuser`
--

INSERT INTO `umg_tbuser` (`UserID`, `Username`, `UserEmail`, `IsActive`, `IsDeleted`, `CreatedBy`, `CreatedOn`, `LastModifiedBy`, `LastModifiedOn`, `UserGUID`, `PlainPassword`) VALUES
(1, 'Bilal', '', 1, 0, 1, '2019-01-07 00:00:00', 1, '2019-01-07 00:00:00', 'fa81ad79-1274-11e9-84c3-90b11c7079ae', 'abc123'),
(2, 'khurram', '', 1, 0, 1, '2019-02-07 10:51:56', 1, '2019-02-07 10:51:56', '7cfd7229-2a9c-11e9-8ca0-90b11c7079ae', 'abc123');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
