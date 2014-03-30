CREATE TABLE IF NOT EXISTS `#__nextend_smartslider_layouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `slide` LONGTEXT NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE #__nextend_smartslider_layouts CHANGE `slide` `slide` LONGTEXT;

CREATE TABLE IF NOT EXISTS `#__nextend_smartslider_sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `type` varchar(30) NOT NULL,
  `params` text NOT NULL,
  `generator` text NOT NULL,
  `slide` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE #__nextend_smartslider_sliders CHANGE `slide` `slide` LONGTEXT;

CREATE TABLE IF NOT EXISTS `#__nextend_smartslider_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slider` int(11) NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `first` int(11) NOT NULL,
  `slide` LONGTEXT NOT NULL,
  `description` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `background` varchar(300) NOT NULL,
  `params` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `generator` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE #__nextend_smartslider_slides CHANGE `slide` `slide` LONGTEXT;


CREATE TABLE IF NOT EXISTS `#__nextend_smartslider_storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(200) NOT NULL,
  `value` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__nextend_smartslider_storage` (`id`, `key`, `value`) VALUES
(1, 'layout', '{"size":"1024|*|768"}'),
(2, 'settings', '{"debugmessages":"1","slideeditoralert":"1","translateurl":"|*|","jquery":"1","placeholder":"http:\\/\\/www.nextendweb.com\\/static\\/placeholder.png","tidy-input-encoding":"utf8","tidy-output-encoding":"utf8"}'),
(3, 'font', '{"sliderfont1customlabel":"Heading light","sliderfont1":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"ffffffff\\",\\"size\\":\\"320||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.3\\",\\"bold\\":1,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"paddingleft\\":0,\\"size\\":\\"100||%\\"},\\"Link:Hover\\":{\\"paddingleft\\":0,\\"size\\":\\"100||%\\"}}","sliderfont2customlabel":"Heading dark","sliderfont2":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"000000db\\",\\"size\\":\\"320||%\\",\\"tshadow\\":\\"0|*|1|*|0|*|ffffff33\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.3\\",\\"bold\\":1,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont3customlabel":"Subheading light","sliderfont3":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"ffffffff\\",\\"size\\":\\"170||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.2\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont4customlabel":"Subheading dark","sliderfont4":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"000000db\\",\\"size\\":\\"170||%\\",\\"tshadow\\":\\"0|*|1|*|0|*|ffffff33\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.2\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont5customlabel":"Paragraph light","sliderfont5":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"ffffffff\\",\\"size\\":\\"114||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.4\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"justify\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont6customlabel":"Paragraph dark","sliderfont6":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"000000db\\",\\"size\\":\\"114||%\\",\\"tshadow\\":\\"0|*|1|*|0|*|ffffff33\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.4\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"justify\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont7customlabel":"Small text light","sliderfont7":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"ffffffff\\",\\"size\\":\\"90||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.2\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont8customlabel":"Small text dark","sliderfont8":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"000000db\\",\\"size\\":\\"90||%\\",\\"tshadow\\":\\"0|*|1|*|0|*|ffffff33\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.1\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont9customlabel":"Handwritten light","sliderfont9":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"ffffffff\\",\\"size\\":\\"140||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Pacifico);),Arial\\",\\"lineheight\\":\\"1.3\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont10customlabel":"Handwritten dark","sliderfont10":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"000000db\\",\\"size\\":\\"140||%\\",\\"tshadow\\":\\"0|*|1|*|0|*|ffffff33\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Pacifico);),Arial\\",\\"lineheight\\":\\"1.3\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont11customlabel":"Button light","sliderfont11":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"ffffffff\\",\\"size\\":\\"100||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.3\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"center\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfont12customlabel":"Button dark","sliderfont12":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"000000db\\",\\"size\\":\\"100||%\\",\\"tshadow\\":\\"0|*|1|*|0|*|ffffff33\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.3\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"center\\",\\"paddingleft\\":0},\\"Link\\":{\\"paddingleft\\":0,\\"size\\":\\"100||%\\"},\\"Link:Hover\\":{\\"paddingleft\\":0,\\"size\\":\\"100||%\\"}}","sliderfontcustom1customlabel":"My first custom font","sliderfontcustom1":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"1abc9cff\\",\\"size\\":\\"360||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Pacifico);),Arial\\",\\"lineheight\\":\\"1.3\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"left\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfontcustom2customlabel":"My second custom font","sliderfontcustom2":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"ffffffff\\",\\"size\\":\\"140||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.2\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"center\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfontcustom3customlabel":"My third custom font","sliderfontcustom3":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"1abc9cff\\",\\"size\\":\\"120||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.2\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"center\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}","sliderfontcustom4customlabel":"My fourthcustom font ","sliderfontcustom4":"{\\"firsttab\\":\\"Text\\",\\"Text\\":{\\"color\\":\\"1abc9cff\\",\\"size\\":\\"120||%\\",\\"tshadow\\":\\"0|*|1|*|1|*|000000c7\\",\\"afont\\":\\"google(@import url(http:\\/\\/fonts.googleapis.com\\/css?family=Open Sans);),Arial\\",\\"lineheight\\":\\"1.2\\",\\"bold\\":0,\\"italic\\":0,\\"underline\\":0,\\"align\\":\\"right\\",\\"paddingleft\\":0},\\"Link\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0},\\"Link:Hover\\":{\\"size\\":\\"100||%\\",\\"paddingleft\\":0}}"}');

ALTER TABLE #__nextend_smartslider_storage CHANGE `value` `value` LONGTEXT;

UPDATE `#__nextend_smartslider_storage` SET value = 1 WHERE `key` LIKE 'sliderchanged%'