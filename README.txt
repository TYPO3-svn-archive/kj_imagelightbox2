




Alter lightbox.js

add 221-224
    // add presentationMode var for kj_imagelightbox2
    presentationMode:false,
    
    
add 224-229
	    // add presentationMode var for kj_imagelightbox2
      if(imageLink.rel.toLowerCase().match('presentation')){
				presentationMode = true;
			} else {
				presentationMode = false;
			}
			
			
add 345-357
      // add presentationMode var for kj_imagelightbox2
      if(presentationMode){
       		newCapArray = [];
			for(var i=1;i<=this.imageArray.length;i++){
				if((i-1) == this.activeImage){
					newCap = '<span class="presentationmodeSpan presentationmodeAct"><a href="#" class="presentationmodeAct" id="'+i+'" onClick="kjLightbox.changeImage('+(i-1)+'); return false;">'+i+'</a></span>';
				} else {
					newCap = '<span class="presentationmodeSpan presentationmode"><a href="#" class="presentationmode" id="'+i+'" onClick="kjLightbox.changeImage('+(i-1)+'); return false;">'+i+'</a></span>';
				}
				newCapArray.push(newCap);
   			}
        	this.caption.update(newCapArray.join(" ")).show();
    	}
    	
    	
    	
alter 525 add kjLightbox
// add kjLightbox var for kj_imagelightbox2
document.observe('dom:loaded', function () { kjLightbox = new Lightbox(); });
