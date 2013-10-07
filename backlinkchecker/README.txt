
		Backlinkchecker README

Der Backlinkchecker dient dazu, die eingehenden Links zu einer Webseite zu 
finden. Der Backlinkchecker wird als Inline-Popup auf der Webseite angezeigt
und es muss nur der Button in das HTML Design eingebunden werden. Nach der 
Installation kann man im Browser die Datei http://meinserver.de/.../index.html 
aufrufen und die Funktion des Backlinkcheckers validieren.

Um den Backlinkchecker auf den eigenen Webseiten einzusetzen, ist der folgende
HTML Code in die Webseite(n) einzubinden!

1. Folgender Code muss in den Header aller HTML Dateien eingefuegt werden, die
den Backlinkchecker aufrufen sollen.

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Backlinkchecker Example</title>
    <link rel="stylesheet" type="text/css" href="blc/ext-2.2/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="blc/ext-2.2/resources/css/xtheme-default.css" />
    <script type="text/javascript" language="javascript" src="blc/ext-2.2/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" language="javascript" src="blc/ext-2.2/ext-all-debug.js"></script>
    <script type="text/javascript" language="javascript" src="blc/js/backlinkchecker.js"></script>


2. Folgender HTML Code muss im HTML Body der HTML Datei eingebaut werden. Der Code beeinflusst das 
Layout der Webseite nicht und kann an beliebiger Stelle im Code eingefuegt werden.

    <div id="hello-win" class="x-hidden">
    <div class="x-window-header">Webmaster Toolbox (GPL V2) - &copy; 2009 by
	<a style="text-decoration:none; color:#15428B;" href="http://www.webhosting-forum.net/" target="_blank">Webhosting-Forum</a>.net
    </div>
    </div>

3. Der Button  mit dem der Backlinkchecker geoeffnet wird. Dieser Button sollte
deutlich sichtbar auf der Webseite eingefuegt werden. Falls der Button vom 
Layout her nicht passt, ist es sinnvoll die Darstellung des 
Buttons mit Hilfe von CSS anzupassen. Eine sehr gute CSS Doku ist unter 
http://www.css4you.de/ zu finden.

    <input type="button" id="show-btn" value="Backlinkchecker" />


Die index.html Datei enthaelt alle HTML Elemente und kann als Beispielvorlage 
verwendet werden.
