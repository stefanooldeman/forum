<!-- hide from non JavaScript browsers 
<!------------------------------------------------------>
<!--               RTWedit                            -->
<!--             version 0.3.4                        -->
<!------------------------------------------------------>
<!--  Added support to receive a form object in       -->
<!-- addition to form label.                          -->
<!------------------------------------------------------>
<!--                                                  -->
<!--  Rich Text WYSIWYG HTML editor                   -->
<!--                                                  -->
<!--  (c) Oxford University 2003-2005                 -->
<!--  scripted by Paul Trafford                       -->
<!--  paul.trafford@oucs.ox.ac.uk                     -->
<!--                                                  -->
<!--  This software is released as open source        -->
<!--  under GNU Lesser General Public Lcense (LGPL)   -->
<!--  See http://www.gnu.org/copyleft/lesser.html     -->
<!------------------------------------------------------>
<!--  See distribution for explanatory notes.         -->
<!------------------------------------------------------>

//-------------------------------------------------------
// Set up browser detection
//-------------------------------------------------------
function BrowserDetectLite() {
  var ua        = navigator.userAgent.toLowerCase(); 
  var is_major  = parseInt(navigator.appVersion);
  var is_ie     = ((ua.indexOf("msie") != -1) && (ua.indexOf("opera") == -1));
   
// Certain browser names/versions we're interested in
  this.isGecko  = (ua.indexOf('gecko') != -1 && ua.indexOf('safari') == -1);
  this.isIE     = ( (ua.indexOf('msie') != -1) && (ua.indexOf('opera') == -1) && (ua.indexOf('webtv') == -1) ); 
  this.isIE4to5 = (is_ie && (is_major >= 4) && (is_major <= 5) );
}
   
//-------------------------------------------------------
// Apply design mode formatting to editor content
//-------------------------------------------------------
function doFormat(formlabel,fieldlabel,what) {

  formlabel2=formlabel.replace(/[\[\]]/g, "\_");	
// store a copy of the markup in a form's hidden field
  docform     = eval('document.'+formlabel+'.backup'+fieldlabel);
  preedit     = eval('WinPre.'+formlabel2);
  var browser = new BrowserDetectLite();
  
// provide support for old IE method (new versions of IE don't need this):
  if (browser.isIE4to5)
  {
    Edframe       = eval('WinPre.frames.'+formlabel2);
	docform.value = Edframe.document.body.innerHTML;
    var tr        = Edframe.document.selection.createRange()
    tr.select()
  }
  else
  {
    var tr=WinPre.document.getElementById(formlabel2).contentWindow.document;	
    Edframe=eval('WinPre.document.getElementById(formlabel2).contentWindow.document');
	EdframeHTML=Edframe.body.innerHTML;
// allow for use of CSS, which needs offsets	
	if (csslink)
    {	
      offset=EdframeHTML.indexOf(csslink);
      docform.value=EdframeHTML.substring(csslink.length+offset);
	  if (offset != 0){docform.value=EdframeHTML.substring(0,offset)+docform.value;}
	}
	else
	{
	docform.value=EdframeHTML;
	}
  }
  WinPre.focus();

// apply formatting - for IE, capture functions with extra bells and whistles
  if ((arguments[3]==null) || ((what == "Createlink") && browser.isIE) )
  {
	tr.execCommand(what)
  } 
  else
// apply formatting - syntax for all others (Midas-based):
  {
// Non-IE's createlink option works differently from others
// so we have to trap this special case 
    if (what == "Createlink") 
    { 
      var userURL = WinPre.prompt("Enter a URL:", "");
      tr.execCommand("Unlink",false,null)
      tr.execCommand("CreateLink",false,userURL)
    }
    else if (what == "insertimage") 
    { 
      if (eval('document.'+formlabel+'.imgprefix'+fieldlabel))
	  {
	    imgprefix=eval('document.'+formlabel+'.imgprefix'+fieldlabel).value
	  }
	  else
	  {
	    imgprefix="";
      }
      var imgURL = WinPre.prompt("Enter Image location:", imgprefix);
      tr.execCommand("Unlink",false,null)
      tr.execCommand("insertimage",false,imgURL)
    }
    else
    {
	  tr.execCommand(what, false, arguments[3])
    }
  }
  WinPre.focus()
  if (browser.isIE) {    tr.select() }
  copyValue(formlabel,fieldlabel)
  if (browser.isIE) {Edframe.focus() }
}

//-------------------------------------------------------
// Copy editor contents into textarea box 
//-------------------------------------------------------  
function copyValue(formlabel,fieldlabel) {
  formlabel2=formlabel.replace(/[\[\]]/g, "\_");	
  field=eval('document.'+formlabel+'.'+fieldlabel);
  form =eval('WinPre.'+formlabel2);
    
  var browser = new BrowserDetectLite();
  if (browser.isGecko) 
  {	
	WinPreDoc=WinPre.document.getElementById(formlabel2).contentWindow.document;
	HTMLcontent=WinPreDoc.body.innerHTML;
    WinHTMLcontent=HTMLcontent;
// check if editor has been inserting markup before stylesheet and correct accordingly
// (another messy solution :-( )
    if (csslink)
    {	
      offset=WinHTMLcontent.indexOf(csslink)
      if (offset != 0)
      {
        HTMLcontent=csslink+WinHTMLcontent.substring(0,offset);
// add a tail if need be
        taillength=(WinHTMLcontent.length)-(csslink.length)-offset;
        if (taillength >0)
        {
		  tailcontent=WinHTMLcontent.substring(offset+(csslink.length));
          HTMLcontent+=tailcontent
        }
      }
    }
    field.value = HTMLcontent.substring(csslink.length);
  } 
  else
  {
    field.value= form.document.body.innerHTML;
  }    
   
// where found, replace physical with semantic markup
  field.value=field.value.replace(/\<(\/?)B\>/ig,"<$1strong>");
  field.value=field.value.replace(/\<(\/?)I\>/ig,"<$1em>");    

// XHTML - convert tags to lower case    
  field.value=field.value.replace(/\<(\/?)STRONG\>/g,"<$1strong>");
  field.value=field.value.replace(/\<(\/?)P\>/g,"<$1p>");
  field.value=field.value.replace(/\<(\/?)U\>/g,"<$1u>");
  field.value=field.value.replace(/\<(\/?)EM\>/g,"<$1em>");
  field.value=field.value.replace(/\<(\/?)H([0-9])\>/g,"<$1h$2>");
  field.value=field.value.replace(/\<(\/?)A/g,"<$1a");
  field.value=field.value.replace(/\<IMG/g,"<img");
  field.value=field.value.replace(/\<(.*)HREF=\"(.*)\>/g,"<$1href=\"$2>");
  field.value=field.value.replace(/\<(.*)IMG=\"(.*)\>/g,"<$1img=\"$2>");
  field.value=field.value.replace(/\<(.*)SRC=\"(.*)\>/g,"<$1src=\"$2>");
  field.value=field.value.replace(/\<(\/?)UL\>/g,"<$1ul>");
  field.value=field.value.replace(/\<(\/?)OL\>/g,"<$1ol>");
  field.value=field.value.replace(/\<(\/?)LI\>/g,"<$1li>");
  field.value=field.value.replace(/\<(\/?)BR\>/g,"<$1br>");	 
 
// XHTML - close tags
  field.value=field.value.replace(/\<([bh]r)\>/g,"<$1 />");
}

//-------------------------------------------------------
// Undo last change  
//-------------------------------------------------------  
function undo(formlabel,fieldlabel) {
  formlabel2=formlabel.replace(/[\[\]]/g, "\_");	
  form=eval('document.'+formlabel);
  field=eval('document.'+formlabel+'.'+fieldlabel);
  fieldbakstring = 'document.'+formlabel+'.backup'+fieldlabel;
  fieldbak=eval(fieldbakstring);

// allow undo to non-empty values only  
  if (fieldbak.value != '') {
    field.value= fieldbak.value;
    var browser = new BrowserDetectLite();
    if (browser.isGecko) 
    {
      WinPre.document.getElementById(formlabel2).contentWindow.document.body.innerHTML=csslink+fieldbak.value;
    }
    else
    {    
      framedoc=eval('WinPre.'+formlabel2+'.document');
      frameform=eval('WinPre.frames.'+formlabel2);
      framedoc.body.innerHTML=fieldbak.value;
	}
  }
  WinPre.focus()
}

//-------------------------------------------------------
// Initialise and generate pop-up editing widget  
//-------------------------------------------------------
function preview(formlabel,fieldlabel,WinPre_width,WinPre_height) {

// Both form objects and form labels can be passed 
// First, test to see if object is passed
if (/object/.test(formlabel))
{
// work out index of current form so that we can write 
// to the appropriate form fields in the calling document
var formIndex;
var numberForms = document.forms.length;
for(formIndex = 0; formIndex < numberForms; formIndex++)
{
 if(document.forms[formIndex] == formlabel) 
  {
   break;
  }
}
// if we have an object then just reassign as form name
// with appropriate reference to correct form using index 
 formlabel='forms['+formIndex+']';
// following label is for popup form, and it cannot have [] chars
 formlabel2= formlabel.replace(/[\[\]]/g, "\_");
 formstring = 'document.forms['+formIndex+']';
}
else
// a label has been passed
{
formstring = 'document.'+formlabel;
formlabel2=formlabel;
} 

// defaults for popup window dimensions
// (for a useful article on element dimensions, see:
// http://msdn.microsoft.com/workshop/author/om/measuring.asp )
  var browser = new BrowserDetectLite();
  if (!WinPre_width || !WinPre_height) 
  {
    WinPre_width=600;
    WinPre_height=350;
  }

// offsets for the inline frame  
  Win_xoffset=30;
  Win_yoffset=60;

//-----------------------------------------------------------------------  
// extract information
//-----------------------------------------------------------------------  

field = eval(formstring+'\.'+fieldlabel);
previewstatus = eval(formstring+'\.prev'+fieldlabel);	 	

// check to see if there are any paragraph tags.
// if not, for the first preview, insert line breaks
  thetext=field.value;
  if (previewstatus.value == '' &! (/\<[Pp]\>/.test(thetext)))
  {
    if (thetext!="")
    {
      thetext="<p>"+thetext;
// insert line breaks (catering for different platforms)
      thetext = thetext.replace(/(\n\n)/g, "</p>$1<p>");
      thetext = thetext.replace(/\<*(\r\n\r\n)/g, "</p>$1<p>");
      thetext = thetext+"</p>";
    }
    field.value=thetext;
    previewstatus.value='done';
  }


// determine whether or not to link to user-defined css file	
  cssstring = eval('document.'+formlabel+'.css'+fieldlabel);
  if (cssstring)
  {
    csslink='<style type="text/css"><!--@import url("'+cssstring.value+'");--></style>';
  }	
  else
  {
	csslink='';
  }

  contentstring=field.value;
  var WinPreName = fieldlabel;    
   
// Launch pop-up window. 'about:blank' parameter to prevent 'access denied' errors in IE 
  WinPre = window.open('about:blank',WinPreName.value,'scrollbars=yes,resizable=1,width='+WinPre_width+',height='+WinPre_height);
  var WinPreDoc = WinPre.document;    
  var i;
		
  if (!WinPreDoc.designMode)
  {
    WinPreDoc.writeln('<html><head>');
    WinPreDoc.writeln('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">');
    WinPreDoc.writeln('<title>');
    WinPreDoc.writeln('Preview Window ('+WinPreName+')');
    WinPreDoc.writeln('</title>');
    WinPreDoc.writeln('</head>');
    WinPreDoc.writeln('<body>Sorry, RTWedit is not supported by your browser, so we only offer a preview below.  This tool is supported by, e.g., <a href="http://mozilla.org" target="_new">Mozilla 1.3 or higher</a>.<hr />');
    WinPreDoc.writeln(contentstring);
    WinPreDoc.writeln('</body></html>');
    WinPreDoc.close();
    WinPre.focus();
    return;
  }
  else
  {
	iframe_width=WinPre_width-Win_xoffset;
	iframe_height=WinPre_height-Win_yoffset;

// determine the array of display items
    optionstring = eval('document.'+formlabel+'.options'+fieldlabel);
    if (optionstring)
    {
      var options=optionstring.value.split(",");
      totalitems=options.length;
      labeloptionsstring = eval('document.'+formlabel+'.labeloptions'+fieldlabel);
      var labeloptions=labeloptionsstring.value.split(",");  
    }
    else 
    {
      var totalitems=6;
      var options= new Array(totalitems);
      var labeloptions= new Array(totalitems);
      options[0]= 'Bold';   labeloptions[0]= ' B ';
      options[1]= 'Italic';   labeloptions[1]= ' I ';
      options[2]= 'Underline';   labeloptions[2]= ' U ';
      options[3]= 'insertunorderedlist';   labeloptions[3]= ' List ';
      options[4]= 'Createlink';   labeloptions[4]= ' Link ';
      options[5]= 'insertimage';   labeloptions[5]= ' IMG ';
    }    

// options library - this is currently just a reference ...
    var liboptions= new Array(10);
    var liblabeloptions = new Array(10);
    liboptions[0]= 'Bold';   liblabeloptions[0]= ' B ';
    liboptions[1]= 'Italic';   liblabeloptions[1]= ' I ';
    liboptions[2]= 'Underline';   liblabeloptions[2]= ' U ';
    liboptions[3]= 'insertunorderedlist';   liblabeloptions[3]= ' List ';
    liboptions[4]= 'Createlink';   liblabeloptions[4]= ' Link ';
	liboptions[5]= 'insertimage';   liblabeloptions[5]= ' IMG ';
 
// The popup windows includes one event handler and associated function to auto resize the inline frame

    WinPreDoc.writeln('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">');
    WinPreDoc.writeln('<html><head>');
    WinPreDoc.writeln('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
    WinPreDoc.writeln('<title>');
    WinPreDoc.writeln('Preview Window ('+WinPreName+')');
    WinPreDoc.writeln('</title>');
	WinPreDoc.writeln(csslink);
    WinPreDoc.writeln('</head>');	 
	
// as at IE6, Moz1.3, it is not possible to capture many events in iframes, e.g. 
// can't detect key presses, thus we cannot auto update from WYSIWYG to HTML source - 
// hence we have the [Apply] button.
    WinPreDoc.writeln('<body>');	
    WinPreDoc.writeln('<script language="JavaScript" type="text/javascript">');
	WinPreDoc.writeln('	onresize = function() {resize_iframe()}');
    WinPreDoc.writeln('	onkeypress = function() {window.opener.copyValue(\''+formlabel+'\',\''+fieldlabel+'\')}');
	WinPreDoc.writeln(' function resize_iframe(){');
	WinPreDoc.writeln('  xoffset='+Win_xoffset);
	WinPreDoc.writeln('  yoffset='+Win_yoffset);
	WinPreDoc.writeln('  document.getElementById("'+formlabel2+'").width=document.body.clientWidth-xoffset;')
	WinPreDoc.writeln('  document.getElementById("'+formlabel2+'").height=document.body.clientHeight-yoffset;')
	WinPreDoc.writeln(' }');
	WinPreDoc.writeln('</script>');
    WinPreDoc.writeln('<form id="previewform" onsubmit=\"window.opener.copyValue(this); return false\" action="">');

// print paragraph styles - read in option, else default to headings 1 thru' 4
    var parastyleobject = eval('document.'+formlabel+'.parastyles'+fieldlabel); 
    if (parastyleobject)
	{
	  var parastylestring=parastyleobject.value;
	}
	else 
	{
	  var parastylestring="1,2,3,4";
	}
    headings=parastylestring.split(",");
    totalheadings=headings.length;
 
// print out styles unless field label set to 'off'
    if (parastylestring != "off")
	{
	  WinPreDoc.writeln(' <select id="formatblock" onchange="window.opener.doFormat(\''+formlabel+'\',\''+fieldlabel+'\',\'FormatBlock\',this.options[this.selectedIndex].value)">');
	  WinPreDoc.writeln('	<option value="<p>">Normal</option>');
	  WinPreDoc.writeln('	<option value="<p>">Paragraph</option>');
      for (i=0;i<totalheadings;i++)
      {
        WinPreDoc.writeln('<option value="<h'+headings[i]+'>">Heading '+headings[i]+'</option>');
      }
	  WinPreDoc.writeln(' </select>');
	}
	 
// print out buttons
    for (i=0;i<totalitems;i++)
    {
      WinPreDoc.writeln(' <input onclick="window.opener.doFormat(\''+formlabel+'\',\''+fieldlabel+'\',\''+options[i]+'\',\'\')" type=button value="'+labeloptions[i]+'">');
    }
    WinPreDoc.writeln(' <input onclick="window.opener.undo(\''+formlabel+'\',\''+fieldlabel+'\')" type=button value="Undo"> ');

//  Uncomment the following if you need a [Save] button for IE  
// (but there is no [Open] equivalent ...                     
/* 
    if (browser.isIE)
    {
      WinPreDoc.writeln(' <input type="button" value="Save" OnClick="'+formlabel+'.document.execCommand(\'SaveAs\',null,\'.html\')">');
    }
*/

    WinPreDoc.writeln(' <input type="button" value="Apply" OnClick="window.opener.copyValue(\''+formlabel+'\',\''+fieldlabel+'\'); return false"> ');
    WinPreDoc.writeln(' <input type="button" value="Close" OnClick="window.opener.copyValue(\''+formlabel+'\',\''+fieldlabel+'\');window.close()"><br />');
    WinPreDoc.writeln('<iframe src=\'about:blank\' width="'+iframe_width+'" height="'+iframe_height+'" id=\''+formlabel2+'\'></iframe><br />');

    WinPreDoc.writeln('</form>');
    WinPreDoc.writeln('</body></html>');
    WinPreDoc.close();
    WinPre.focus();
	

    var markupstring=csslink+contentstring;
    var browser = new BrowserDetectLite();
    if (browser.isGecko) 
	{
//check to see if midas is enabled
      try {
            WinPre.getElementById(formlabel2).contentWindow.document.execCommand("undo", false, null);
            isMidasEnabled = true;
        }  catch (e) {
            isMidasEnabled = false;
        }
        
// the following is a temporary fix - without it, the content selection doesn't appear in the iframe..
      Wintemp = window.open('about:blank','tempWin','width=1,height=1');
      Wintemp.close();

// cycle while loading iframe (needed for Mozilla browsers)
      while (!WinPreDoc.getElementById(formlabel2))
      {
        x=Math.random();
      }

      WinPreDoc.getElementById(formlabel2).contentDocument.designMode = "on";

// cycle while loading iframe document content (needed for Mozilla browsers)
      while (!WinPreDoc.getElementById(formlabel2).contentWindow.document)
	  {
	    x=Math.random();
	  }

// initialise and then populate the content in the iframe
      WinPreDocBody=WinPreDoc.getElementById(formlabel2).contentWindow.document.body;

// replace some tags with hard coded ones - designed to get round
// limitations in Mozilla's execCommand 
      markupstring=markupstring.replace(/\<(\/?)em\>/ig,"<$1i>");
      markupstring=markupstring.replace(/\<(\/?)strong\>/ig,"<$1b>");

      WinPreDocBody.innerHTML=markupstring;

// set up some basic CSS formatting - but so far only works in Midas...
      if (csslink == '')
      {
        WinPreDocBody.style.backgroundColor = "#ffffff";
        WinPreDocBody.style.fontFamily = "Arial Unicode MS, sans-serif";
        WinPreDocBody.style.color = "#000000";
      }
	
// Ensure that markup is HTML not CSS
      WinPreDoc.getElementById(formlabel2).contentWindow.document.execCommand("useCSS", false, true);
      WinPre.focus();
    } // end browser is Gecko   
    else
    {
	  myEd=eval('WinPre.frames.'+formlabel2+'.document');
      myEd.designMode = "On";
// quick fix for unstable behaviour - sometimes myEd.body is not defined initially ..
      while (!myEd.body){}
	  myEd.write('<html>'+csslink+'<body>'+contentstring+'</body></html>');
    }
  }
}


//-------------------------------------------------------
// Option to add widget dynamically to web forms
// by Matthew Buckett
//-------------------------------------------------------

// The HTML to be added that adds editor.
function editorHTML (form, field, button_label) {
    return '<input onclick="preview(this.form, \''+ field+ "')\" type=button value=\""+button_label+"\">"+
		'<input type="hidden" name="backup' + field+ '">'+
		'<input type="hidden" name="prev'+ field+ '" value="">';
}

// Should be called from the body onload to add the editor to the page.
// It will ignore any textarea that has text in it's class attribute
function addHtmlEditor(button_label) {
    if (!button_label) {
	    var button_label="Edit";
	}
	var allTextareas, thisTextarea;
	var formCount=0;
	allTextareas = document.getElementsByTagName('textarea');
	for (var i = 0; i < allTextareas.length; i++) {
	    thisTextarea = allTextareas[i];
	    var classes = thisTextarea.getAttribute("class");
	    if (classes && classes.indexOf("text") >= 0)
	      continue;
    	var container = document.createElement("div");
	    container.innerHTML = editorHTML(thisTextarea.form.name, thisTextarea.name, button_label);
	    thisTextarea.parentNode.insertBefore(container, thisTextarea.nextSibling);
	}
}




//-->


