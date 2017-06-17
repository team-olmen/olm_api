DROP TABLE IF EXISTS `olm_exams`;
CREATE TABLE `olm_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_exams_history`;
CREATE TABLE `olm_exams_history` (
  `id` int(11) NOT NULL,
  `history_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `history_user` int(11) NOT NULL,
  `history_status` enum('created','updated','deleted','deletioncancelled','revived') COLLATE utf8_bin NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`history_timestamp`,`history_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_generations`;
CREATE TABLE `olm_generations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_mcqs`;
CREATE TABLE `olm_mcqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` int(11) NOT NULL,
  `raw` varchar(20000) COLLATE utf8_bin NOT NULL,
  `rating` int(11) NOT NULL,
  `original` tinyint(1) NOT NULL,
  `complete` tinyint(1) NOT NULL,
  `generation` int(11) NOT NULL,
  `discussion` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_mcqs_history`;
CREATE TABLE `olm_mcqs_history` (
  `id` int(11) NOT NULL,
  `history_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `history_user` int(11) NOT NULL,
  `history_status` enum('created','updated','deleted','deletioncancelled','revived') COLLATE utf8_bin NOT NULL,
  `module` int(11) NOT NULL,
  `raw` varchar(20000) COLLATE utf8_bin NOT NULL,
  `rating` int(11) NOT NULL,
  `original` tinyint(1) NOT NULL,
  `complete` tinyint(1) NOT NULL,
  `generation` int(11) NOT NULL,
  `discussion` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`history_timestamp`,`history_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_mcqs_rated`;
CREATE TABLE `olm_mcqs_rated` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `rated` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`,`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_modules`;
CREATE TABLE `olm_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `code` varchar(4) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_modules_history`;
CREATE TABLE `olm_modules_history` (
  `id` int(11) NOT NULL,
  `history_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `history_user` int(11) NOT NULL,
  `history_status` enum('created','updated','deleted','deletioncancelled','revived') COLLATE utf8_bin NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `code` varchar(4) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`history_timestamp`,`history_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_protocolls`;
CREATE TABLE `olm_protocolls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `text` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`exam`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_protocolls_history`;
CREATE TABLE `olm_protocolls_history` (
  `id` int(11) NOT NULL,
  `history_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `history_user` int(11) NOT NULL,
  `history_status` enum('created','updated','deleted','deletioncancelled','revived') COLLATE utf8_bin NOT NULL,
  `exam` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `text` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`,`history_timestamp`,`history_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_sessions`;
CREATE TABLE `olm_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `questions` varchar(15000) COLLATE utf8_bin NOT NULL,
  `answers` varchar(2500) COLLATE utf8_bin NOT NULL,
  `status` varchar(2500) COLLATE utf8_bin NOT NULL,
  `current` int(11) NOT NULL,
  `answered` int(11) NOT NULL,
  `correct` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_docs`;
CREATE TABLE `olm_docs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8_bin NOT NULL,
  `text` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `olm_texts`;
CREATE TABLE `olm_texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(200) COLLATE utf8_bin NOT NULL,
  `text` mediumtext COLLATE utf8_bin NOT NULL,
  `help` mediumtext COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `olm_texts` (`id`, `path`, `text`, `help`) VALUES
(1, 'dashboard', 'Du brauchst Hilfe beim "**MC-Fragen kreuzen oder ansehen**" / "**Benutzer einladen**" oder "**Benutzerdaten ändern**"? Klick oben rechts auf "**?!**".\n\nViel Spaß mit dem neuen Olm! :)\n\nDein *Olm-Team*\n\nP.S.: Du willst nicht immer am Laptop olmen? Probiers mal mit dem Handy / Tablet ;)', '# Ich will MC-Fragen kreuzen\n\n*  klicke auf "*Test zusammenstellen*"\n*  such Dir deine MC-Sammlungen aus (Du kannst mehrere auswählen)\n*  ... und los gehts!\n\n# Ich will MC-Fragen sehen\n\n*  MC-Fragen sind in Sammlungen sortiert, meistens heißen die so, wie das Modul oder Semester\n*  Du kannst die Sammlungen Filtern nach:\n    * positiv bewertete -> klicke auf "*gute*"\n    * schlecht bewertete -> klicke auf "*schlechte*"\n    * ist dir egal -> klicke auf "*alle*"\n    * verstanden ;)\n \n# Ich will eine MC-Frage eingeben\n\n* gehe zu der Sammlung, zu der die Frage gehört\n* klicke auf "*erstellen*"\n* Dir fehlt eine Sammlung? Schreib uns! <admin@olmen.de>\n\n# Ich will meine Daten ändern\n\n* ganz oben in grau findest Du "***DEINNAME** ACCNT*"\n* hier kannst Du Deine Daten ändern ;)\n\n# Ich will jemanden einladen\n\n* klicke oben auf "*JMDN EINLADEN*"\n* erstelle einen Account für Deine Kommilitonin / Deinen Kommilitonen\n* schreib ihr / ihm das Passwort, dass Du Dir für sie / ihn ausgedacht hast!\n* Geschafft!\n\n# Wo sind die Ratings aus dem alten Olm hin?\n\n* die Ratings vom alten ins neue *Olm* zu importieren wäre sehr aufwendig gewesen, sodass wir uns lieber darauf konzentriert haben andere Funktionen zu verbessern\n\n* Es tut uns Leid, dass ein bisschen Mühe, die Du Dir gemacht hast verloren gegangen ist! Wir hoffen, Du hilfst trotzdem weiter mit die Inhalte in Olmen besser zu machen?\n\n# Was ist denn das für ein MÜLL? ! ? ? ! ? !! 1 1 11\n\n* hilf uns Olm besser zu machen! Schreib uns (<admin@olmen.de>) und  / oder werde Teil des Teams! :)'),
(2, 'mcq-create', 'Brauchst Du Hilfe beim Fragen eingeben? Nutze "?!"', '# Wie formatiere ich eine MC-Frage?\n\n**Bitte formatiere die Frage so:**\n\nLanger Absatz.... Bla bla bla...\n\nVielleicht noch ein Absatz.... Mehr Bla bla bla...\n\n\\- unsinnige Antwort\n\n\\- noch eine falsche Antwort\n\n\\* richtige Antwort (zu erkennen an dem *)\n\n\\- noch mehr bla bla'),
(3, 'mcqs-view', '', 'Durch einen klick auf "+" oder "-" kannst Du eine Frage bewerten.'),
(4, 'mcq-edit', 'Brauchst Du Hilfe beim Fragen bearbeiten? Nutze "?!"', 'Bitte formatiere die Frage so:\n\nLanger Absatz.... Bla bla bla...\n\nNoch ein Absatz.... Mehr Bla bla bla...\n\n\\- unsinnige Antwort\n\n\\- noch eine falsche Antwort\n\n\\* richtige Antwort (zu erkennen an dem *)\n\n\\- noch mehr bla bla'),
(5, 'door', '# Hey :) ich bin das neue Olm!\n\nWir haben *Olm* für Dich komplett erneuert!\n\n## Wie melde ich mich an?\n\nWenn Du seit 2016 mindestens einmal *Olm* genutzt hast, ist Dein Account mit umgezogen. Dein Nutzername entspricht erstmal der Emailadresse, die Du angegeben hast.\n\nAus kryptographischen  Gründen konnten wir Dein Passwort nicht mit umziehen lassen. Bitte nutze "neuesolmen" als Passwort und ändere es sofort in ein sicheres Passwort ;) (Achtung! "Aktuelles Passwort" ist "neuesolmen", NICHT dein altes Passwort!)\n\n## Was ist, wenn ich keinen Account habe?\n\nWie beim alten *Olm* auch, kannst Du Dich von einer Kommilitonin / einem Kommilitonen mit Account einladen lassen!\n\n## Wo finde ich Hilfe?\n\nImmer, wenn Du auf einer Seite ein blaues Feld mit "?!" siehst, kannst Du Dir einen Hilfetext zu dieser Seite anschauen.\n\n## Dir fehlen Hilfetexte?  Unsere Hilfetexte sind unverständlich?\nHilf uns besser zu werden! :) Schreib uns Vorschläge oder werde Teil des Teams! Wir freuen uns auf Dich! :) (s. "?!")', 'Wenn Du Fragen hast, schreib uns: <admin@olmen.de>'),
(6, 'session-create', '', '# Wie wähle ich mehrere MC-Sammlungen aus?\n\n*  halte die "*Strg*"-Taste gedrückt während Du mehrere Sammlungen auswählst\n\n   (P.S.: funktioniert auch in vielen anderen Programmen, z.B. wenn man mehrere Textabschnitte kopieren will ;) )'),
(7, 'texts-view', 'Ihr könnt zu jeder Funktion (hier als "*Pfad*" bezeichnet) in *Olm* einen kurzen Text erstellen, der immer am Anfang der Seite angezeigt wird,\n\nZusätzlich könnt Ihr zu jeder Seite eine Hilfe erstellen, die erst angezeigt wird, wenn die Nutzenden (klingt gruselig...) auf "*?!*" klicken.\n\nDamit die Texte nicht so langweilig erscheinen habt Ihr die Möglichkeit sie mit **Markdown** aufzumöbeln (s. "*?!*"). Übrigens: Protokolle können auch Markdown enthalten! :)\n\n*Pfade*: die Pfade sind etwas kryptisch. Von den Titeln, die hier aufgelistet sind könnt ihr zurückschließen, wo die Texte vermutlich stehen werden. Ansonsten findet ihr auf jeder Seite mit einer Funktion (= "*Pfad*") den Button "*Txt / Hlf anlegen*" bzw. "*Txt / Hlf bearbeiten*", wenn es schon Texte gibt.  In beiden Fällen ist der Pfad schon eingetragen, am besten eifnach nicht ändern ;) im Zweifelsfall fragt mich ;)', '# WTH ist Markdown?!\n\nKennt ihr noch alte Foren mit BB-Code? (sowas wie "[b]fetter[/b] Text")? \n\n* Nein? Ihr habt nichts verpasst!\n* Ja? Markdown ist viel besser!!!! :)\n\n## Was ist nun Markdown?\n\nIn letzter Zeit gibt es eine Strömung hin zu "distraction-free writing" -> Die Grundideen sind:\n\n* "*Ich will Überschriften! Nicht fetten Text mit Schriftgröße X*"\n* "*Ich will meine Hand nicht zur Maus bewegen um in irgendeiner Oberfläche den "Liste"-Knopf zu suchen, die sie bei jedem Update verändert*"\n\n## Komm auf den Punkt!\n\nMarkdown ist einfacher Text. Lesbar. Auf jedem System. Von MS DOS über Linux bis hin zu Mac. Immer.\n\n```\n# Überschrift 1\n\n## Überschrift 1.1\n\n### Und so weiter ;)\n\nEs gibt **wichtigen** Text und *betonten* Text.\n\n* Listen sind wirklich einfach\n* wirklich!\n    * geht sogar mit Unterpunkten\n* sag ich ja.\n\n1. und natürlich kann man auch\n2. nummerierte Listen machen\n```\nDu brauchst immernoch mehr?!! <https://help.github.com/articles/basic-writing-and-formatting-syntax/>'),
(8, 'protocoll-create', 'Damit die Texte nicht so langweilig erscheinen hast Du die Möglichkeit sie mit **Markdown** aufzumöbeln (s. "*?!*").', '# WTH ist Markdown?!\n\nKennt ihr noch alte Foren mit BB-Code? (sowas wie "[b]fetter[/b] Text")? \n\n* Nein? Ihr habt nichts verpasst!\n* Ja? Markdown ist viel besser!!!! :)\n\n## Was ist nun Markdown?\n\nIn letzter Zeit gibt es eine Strömung hin zu "distraction-free writing" -> Die Grundideen sind:\n\n* "*Ich will Überschriften! Nicht fetten Text mit Schriftgröße X*"\n* "*Ich will meine Hand nicht zur Maus bewegen um in irgendeiner Oberfläche den "Liste"-Knopf zu suchen, die sie bei jedem Update verändert*"\n\n## Komm auf den Punkt!\n\nMarkdown ist einfacher Text. Lesbar. Auf jedem System. Von MS DOS über Linux bis hin zu Mac. Immer.\n\n```\n# Überschrift 1\n\n## Überschrift 1.1\n\n### Und so weiter ;)\n\nEs gibt **wichtigen** Text und *betonten* Text.\n\n* Listen sind wirklich einfach\n* wirklich!\n    * geht sogar mit Unterpunkten\n* sag ich ja.\n\n1. und natürlich kann man auch\n2. nummerierte Listen machen\n```\nDu brauchst immernoch mehr?!! <https://help.github.com/articles/basic-writing-and-formatting-syntax/>'),
(9, 'protocoll-edit', 'Damit die Texte nicht so langweilig erscheinen hast Du die Möglichkeit sie mit **Markdown** aufzumöbeln (s. "*?!*").', '# WTH ist Markdown?!\n\nKennt ihr noch alte Foren mit BB-Code? (sowas wie "[b]fetter[/b] Text")? \n\n* Nein? Ihr habt nichts verpasst!\n* Ja? Markdown ist viel besser!!!! :)\n\n## Was ist nun Markdown?\n\nIn letzter Zeit gibt es eine Strömung hin zu "distraction-free writing" -> Die Grundideen sind:\n\n* "*Ich will Überschriften! Nicht fetten Text mit Schriftgröße X*"\n* "*Ich will meine Hand nicht zur Maus bewegen um in irgendeiner Oberfläche den "Liste"-Knopf zu suchen, die sie bei jedem Update verändert*"\n\n## Komm auf den Punkt!\n\nMarkdown ist einfacher Text. Lesbar. Auf jedem System. Von MS DOS über Linux bis hin zu Mac. Immer.\n\n```\n# Überschrift 1\n\n## Überschrift 1.1\n\n### Und so weiter ;)\n\nEs gibt **wichtigen** Text und *betonten* Text.\n\n* Listen sind wirklich einfach\n* wirklich!\n    * geht sogar mit Unterpunkten\n* sag ich ja.\n\n1. und natürlich kann man auch\n2. nummerierte Listen machen\n```\nDu brauchst immernoch mehr?!! <https://help.github.com/articles/basic-writing-and-formatting-syntax/>');


DROP TABLE IF EXISTS `olm_users`;
CREATE TABLE `olm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `password` varchar(100) COLLATE utf8_bin NOT NULL,
  `salt` varchar(100) COLLATE utf8_bin NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `account_non_expired` tinyint(1) NOT NULL,
  `credentials_non_expired` tinyint(1) NOT NULL,
  `account_non_locked` tinyint(1) NOT NULL,
  `roles` varchar(20) COLLATE utf8_bin NOT NULL,
  `login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `olm_users` (`id`, `username`, `email`, `password`, `salt`, `enabled`, `account_non_expired`, `credentials_non_expired`, `account_non_locked`, `roles`) VALUES
(1,	'root',	'root@charite.de',	'$2y$13$iYODQBn6rIBfFZ80qfBTIe6qOagqFTbC0vcnB42fahux/0JIEa1Oe',	'',	1,	1,	1,	1,	'ROLE_ADMIN,ROLE_USER'),
(2,	'user',	'user@charite.de',	'$2y$13$iYODQBn6rIBfFZ80qfBTIe6qOagqFTbC0vcnB42fahux/0JIEa1Oe',	'',	1,	1,	1,	1,	'ROLE_USER');
