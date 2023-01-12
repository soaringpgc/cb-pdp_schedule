function getnext() {
	
	var oXHR = zXmlHttp.createRequest();
	var sID = document.getElementById("manuf").value;
	
	oXHR.open("get", "fetch_model.php?manuf=" + sID, true );
	
	oXHR.onreadystatechange = function () {
		if (oXHR.readyState == 4 ) { 
			if (oXHR.status == 200 || oXHR.status == 304) { 
<!-- 				alert ("Data returned is: " + oXHR.responseText); -->
<!--    		document.getElementById("model").innerHTML = oXHR.responseText ;    -->
				select_innerHTML(document.getElementById('model'),oXHR.responseText);
				
				document.getElementById("model").style.display = '';
				document.getElementById("span").innerHTML = "<option value=\"\">Select Wing Span</option>" ;
			} else {
				alert ("An error occurred" + oXHR.responseText);
			}
		}
	};
	oXHR.send(null);

}
function getwingspan() {
	
	var oXHR = zXmlHttp.createRequest();
	var sID = document.getElementById("manuf").value;
	var wID = document.getElementById("model").value;
	
	oXHR.open("get", "fetch_model.php?manuf=" + sID + "&model=" + wID, true );
	
	oXHR.onreadystatechange = function () {
		if (oXHR.readyState == 4 ) { 
			if (oXHR.status == 200 || oXHR.status == 304) { 
<!-- 				alert ("Data returned is: " + oXHR.responseText); -->
<!--       	document.getElementById("span").innerHTML = oXHR.responseText ;      -->
				select_innerHTML(document.getElementById("span"),oXHR.responseText);
					
				document.getElementById("span").style.display = '';
				document.getElementById("gindex").value = document.getElementById("span").value
			} else {
				alert ("An error occurred" + oXHR.responseText);
			}
		}
	};
	oXHR.send(null);

}

function hidelements() {
	if (document.getElementById("model")) {
		document.getElementById("model").style.display = "none";
	}
	if (document.getElementById("span")) {
		document.getElementById("span").style.display = "none";
	}
	countChildElements("menubar", "li");
}

function set_gindex(){

	document.getElementById("gindex").value = document.getElementById("span").value 
}

function countChildElements(parent, child)
     {
          var parent = document.getElementById(parent);
          var childCount = parent.getElementsByTagName(child).length;
		  if (childCount <= 7) {
		  	parent.style.width = "78%";
		  } else {
		    parent.style.width = "89%";
		  }
     }



function select_innerHTML(objeto,innerHTML){
/******
* select_innerHTML - corrige o bug do InnerHTML em selects no IE
* Veja o problema em: http://support.microsoft.com/default.aspx?scid=kb;en-us;276228
* Versão: 2.1 - 04/09/2007
* Autor: Micox - Náiron José C. Guimarães - micoxjcg@yahoo.com.br
* @objeto(tipo HTMLobject): o select a ser alterado
* @innerHTML(tipo string): o novo valor do innerHTML
*******/
    objeto.innerHTML = ""
    var selTemp = document.createElement("micoxselect")
    var opt;
    selTemp.id="micoxselect1"
    document.body.appendChild(selTemp)
    selTemp = document.getElementById("micoxselect1")
    selTemp.style.display="none"
    if(innerHTML.toLowerCase().indexOf("<option")<0){//se não é option eu converto
        innerHTML = "<option>" + innerHTML + "</option>"
    }
    innerHTML = innerHTML.toLowerCase().replace(/<option/g,"<span").replace(/<\/option/g,"</span")
    selTemp.innerHTML = innerHTML
      
    
    for(var i=0;i<selTemp.childNodes.length;i++){
  var spantemp = selTemp.childNodes[i];
  
        if(spantemp.tagName){     
            opt = document.createElement("OPTION")
    
   if(document.all){ //IE
    objeto.add(opt)
   }else{
    objeto.appendChild(opt)
   }       
    
   //getting attributes
   for(var j=0; j<spantemp.attributes.length ; j++){
    var attrName = spantemp.attributes[j].nodeName;
    var attrVal = spantemp.attributes[j].nodeValue;
    if(attrVal){
     try{
      opt.setAttribute(attrName,attrVal);
      opt.setAttributeNode(spantemp.attributes[j].cloneNode(true));
     }catch(e){}
    }
   }
   //getting styles
   if(spantemp.style){
    for(var y in spantemp.style){
     try{opt.style[y] = spantemp.style[y];}catch(e){}
    }
   }
   //value and text
   opt.value = spantemp.getAttribute("value")
   opt.text = spantemp.innerHTML
   //IE
   opt.selected = spantemp.getAttribute('selected');
   opt.className = spantemp.className;
  } 
 }    
 document.body.removeChild(selTemp)
 selTemp = null
}