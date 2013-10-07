/*
 * Backlinkchecker 
 * Copyright(c) 2009, Michael Jentsch
 * GPL 2.0
 * 
 * http://M-Software.de/
 */

/* 
TODO: Session und Captcha
*/

Ext.onReady(function(){

    var win;
    var button = Ext.get('show-btn');
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    var backlinkcheckerurl = Ext.state.Manager.get('backlinkcheckerurl');
    if (typeof backlinkcheckerurl == 'undefined')
    {
    	backlinkcheckerurl = "http://";
    } else {
	if (backlinkcheckerurl.indexOf ("http://") != 0)
	{
    	    backlinkcheckerurl = "http://";
	}
    }

    function existsRenderer(val){
        if(val > 0){
            return '<span style="color:green;">&#8226;</span>';
        }else {
            return '';
        }
    }

    function prRenderer(val){
        // return '<img src="pagerank.php?url=' + val + '" width="20" height="15">';
        return '<div style="position:relative;background-image:url(blc/pagerank.php?url=' + val + ');width:20px;height:15px;"></div>';
    }

    function ipRenderer(val){
        return '<span style="color:green;">' + val + '</span>';
    }

    function urlRenderer(val){
	// TODO: Text des Links bei ueberlaenge abschneiden
        return '<a href="' + val + '" target="_blank">' + val + '</a>';
    }

    // create the data store
    backlinkstore = new Ext.data.JsonStore({
	root: 'backlinks',
        fields: [
           {name: 'url'},
           {name: 'ip'},
           {name: 'pr'},
           {name: 'google'},
           {name: 'yahoo'},
           {name: 'altavista'},
           {name: 'msn'}
        ]
    });

    var backlinkcheckergrid = new Ext.grid.GridPanel({
        store: backlinkstore,
        columns: [
            {
		id:'url',
		header: "URL", 
		width: 160, 
		sortable: true,
		renderer: urlRenderer, 
		dataIndex: 'url'
	    },{
		header: "IP", 
		width: 100, 
		sortable: true, 
		renderer: ipRenderer, 
		dataIndex: 'ip'
	    },{
		header: "PR", 
		width: 30, 
		sortable: true, 
		renderer: prRenderer, 
		dataIndex: 'url'
	    },{
		header: "Google", 
		width: 50, 
		sortable: true, 
		renderer: existsRenderer, 
		dataIndex: 'google'
	    },{
		header: "Yahoo", 
		width: 50, 
		sortable: true, 
		renderer: existsRenderer, 
		dataIndex: 'yahoo'
	    },{
		header: "Altavista", 
		width: 60, 
		sortable: true, 
		renderer: existsRenderer, 
		dataIndex: 'altavista'
	    },{
		header: "MSN", 
		width: 40, 
		sortable: true, 
		renderer: existsRenderer, 
		dataIndex: 'msn'
	}],
        stripeRows: true,
        autoExpandColumn: 'url',
        height:280,
        width:683,
        title:'Backlinks'
    });

    backlinkcheckerform = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        defaultType: 'textfield',
        items: [{
            fieldLabel: 'URL',
            name: 'url',
            value: backlinkcheckerurl,
            anchor:'100%'  // anchor width by percentage
        }]

    });

    // create the data store
    var backlinkoverviewstore = new Ext.data.JsonStore({
	root: 'overview',
        fields: [
           {name: 'name'},
           {name: 'value'}
        ]
    });

    var backlinkcheckeroverviewgrid = new Ext.grid.GridPanel({
        store: backlinkoverviewstore,
        columns: [
            {
		id:'name',
		header: "Name", 
		width: 240, 
		sortable: true,
		dataIndex: 'name'
	    },{
		header: "Wert", 
		width: 400, 
		sortable: true, 
		dataIndex: 'value'
	    }],
        stripeRows: true,
        height:198,
        width:683
    });

    var backlinkcheckeroverviewinfo = new Ext.Panel({
        width:683,
        height:52,
	html: '<p style="padding:4px; font-weight:bold;">Bitte einen URL in der Form "http://www.webhosting-forum.net" in das ' +
	      'Feld "URL" eingeben und dann den Button "Backlinkchecker starten" clicken. ' +
	      'Die Laufzeit des Backlinkcheckers kann je nach Anzahl der Links ein par Sekunden bis zu mehrere Minuten dauern.</p>'
    });

    var backlinkcheckeroverviewpanel = new Ext.Panel({
        width:683,
        height:278,
        title:'&Uuml;bersicht',
	items: [
		backlinkcheckeroverviewinfo,
		backlinkcheckeroverviewgrid
	]
    });

    var backlinkcheckertab = new Ext.TabPanel({
        width:683,
        height:278,
        activeTab: 0,
        frame:true,
        items:[
		// backlinkcheckeroverviewgrid,
		backlinkcheckeroverviewpanel,
		backlinkcheckergrid
        ]
    });


    var webmastertoolbox = new Ext.TabPanel({
        activeTab      : 0,
        deferredRender : false,
        width          : 700,
        border         : false,
        defaults       : {autoHeight: true},
        items          : 
        [{
            title: 'Backlinkchecker',
            items: [
                backlinkcheckerform,
		backlinkcheckertab
            ]
        }, {
                title: 'About',
                html: ' <h1>About Backlinkchecker</h1>' +
		'Copyright 2009 by Michael Jentsch (<a href="http://Webhosting-Forum.net">Webhosting-Forum.net</a>)<br><br>' +
		'<h2>Release:</h2> ' +
		'Backlinkchecker V0.1 (27.01.2009) <br><br>' +
		'<h2>Credits:</h2>' +
		'Vielen Dank an der Stelle an die Entwickler von ExtJS, Snoopy und den vielen Beta Testern aus dem Abakus ' +
		'Internet Marketing Forum. Denen ich viele Anregungen, Tipps und Hinweise verdanke.' +
		'<li><a href="http://extjs.com/">ExtJS</a>' +
		'<li><a href="http://sourceforge.net/projects/snoopy/">Snoopy</a>' +
		'<li><a href="http://www.abakus-internet-marketing.de/foren/">Abakus Internet Marketing Forum</a><br><br>' +
		'<h2>Info:</h2>' +
		'Der Backlinkchecker dient dazu, eine Website daraufhin zu analysieren, wie viele eingehende Links es gibt. ' +
		'Dazu wird in das Feld URL die Webseite eingegeben, zu der die eingehenden Links analysiert werden sollen.<br>' +
		'Hinweis: Die Analyse der eingehenden Links kann mehrere Minuten dauern, da sehr viele Informationen von ' +
		'den unterschiedlichen Quellen ermittelt und verarbeitet werden m&uuml;ssen.<br><br>' +
		'<h2>Lizenz und Download:</h2>' +
		'Der Backlinkchecker ist ein Open Source Projekt und unterliegt der GPL2. Das Projekt ist unter der Open Source ' +
		'Plattform sourceforge.net gehostete und kann unter <a href="http://sourceforge.net/projects/backlinkchecker/">' +
		'http://sourceforge.net/projects/backlinkchecker/</a> heruntergeladen werden.'
			}]
		    });

		    exportButton = new Ext.Button({
			text     : 'CSV Export',
        disabled : true,
        handler  : function(){
	    alert ('TODO');
        }
    });

    button.on('click', function(){
        // create the window on the first click and reuse on subsequent clicks
        if(!win){
            win = new Ext.Window({
                applyTo     : 'hello-win',
                layout      : 'fit',
                width       : 700,
                height      : 400,
		resizable   : false,
                closeAction :'hide',
                plain       : true,
                items       : webmastertoolbox,
                buttons: [{
                    text: 'Backlinkchecker starten',
	            align: 'left',
                    handler  : function(){
		        var values = backlinkcheckerform.getForm().getValues();
		        var url = values.url;
		        Ext.state.Manager.set('backlinkcheckerurl', url);
			backlinkstore.removeAll();
			if (isUrl (url))
			{
				// TODO Wait Dialog
				fetchBacklinks ();
			} else {
				Ext.MessageBox.show({
				   title: 'Ung&uuml;ltiger URL',
				   msg: 'Bitte einen g&uuml;ltigen URL eingeben. (z.B. http://www.webhosting-forum.net/)',
				   buttons: Ext.MessageBox.OK,
				   icon: Ext.MessageBox.ERROR
			       });
			       exportButton.disable();
			}
                    }
                }, exportButton, {
                    text     : 'Beenden',
                    handler  : function(){
                        win.hide();
                    }
                }]
            });
        }
        win.show(button);
    });

    function isUrl(s) 
    {
	// TODO verbessern
	var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	return regexp.test(s);
    }

    function fetchBacklinks () {

	Ext.MessageBox.progress("Bitte warten","Backlinks werden geladen");
	window.setTimeout("updateProgress (0.1)", 200);
	fetchBacklinksReady = false;
	var title = document.getElementsByTagName("title")[0];
	tempTitle = title.text;
	title.text = "Bitte warten: Backlinks werden geladen";
        Ext.Ajax.request({
		timeout: '300000',	
		url: 'blc/backlinks.php',
		form: backlinkcheckerform.getForm().getEl().dom,
		success: function(response, options) {
		    var backlinks = Ext.util.JSON.decode(response.responseText);
		    showOverview (backlinks);
		    backlinkoverviewstore.loadData(backlinks);
		    backlinkstore.loadData(backlinks);

		    fetchBacklinksReady = true;
		    var title = document.getElementsByTagName("title")[0];
		    title.text = tempTitle;
		    Ext.MessageBox.hide();
		    // TODO Ausgehende Links der Seiten messen
		},
		failure: function() {
		    fetchBacklinksReady = true;
		    var title = document.getElementsByTagName("title")[0];
		    title.text = tempTitle;
		    Ext.MessageBox.hide();
		    Ext.MessageBox.alert('Fehler', 'Internal Error: Backlinks k&ouml;nnen nicht gelesen werden!!'); 
		}
        });
    }

    function showOverview (backlinks)
    {
	var text = backlinks.text[0];
	var overview = backlinks.overview;
	backlinkcheckeroverviewinfo.body.update ('<p style="padding:4px; font-weight:bold;">' + text.text + '</p>');
    }

});

function commitPagerankChanges()
{
	backlinkstore.commitChanges();
	exportButton.enable();
}

function updateProgress (i)
{
    Ext.MessageBox.updateProgress(i, 'Backlinks werden geladen');
    i = i + 0.1;
    if (i > 1) i = 0;
    if (fetchBacklinksReady == false)
    {
    	window.setTimeout("updateProgress (" + i + ")", 200);
    }
}

var tempTitle = false;
var exportButton = false;
var backlinkstore = false;
var fetchBacklinksReady = false;
var backlinkcheckerform = false;
