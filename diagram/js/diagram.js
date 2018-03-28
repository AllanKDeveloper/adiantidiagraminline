// your file location of draw.io plugin
var editor = 'http://localhost/diagram/diagram_draw/index.html?embed=1&proto=json';
// init vars
var initial = null;
var name = null;
var saved = false;

function edit(image, key)
{
	// calculations to load the iframe in your size of screen
    var border = 138;
   	var iframe = document.createElement('iframe');
	iframe.style.zIndex = '9999';
	iframe.style.position = 'absolute';
	iframe.style.top = '107px';
	iframe.style.left = '245px';

	if (border == 0)
	{
		iframe.setAttribute('frameborder', '0');
	}

	// do the resize using the calcs above
	var resize = function()
	{
		iframe.setAttribute('width', document.body.clientWidth - 2 * border);
		iframe.setAttribute('height', document.body.clientHeight - 1.2 * border);
	};
	
	// call the resize
	window.addEventListener('resize', resize);
	resize();

	// button actions (save and delete)
    var receive = function(evt)
    {
        if (evt.data.length > 0)
        {
            var msg = JSON.parse(evt.data);
            
            if (msg.event == 'init')
            {
                iframe.contentWindow.postMessage(JSON.stringify({action: 'load',
                    autosave: 1, xmlpng: image}), '*');
            }
            // ajax to save the diagram into database
            else if (msg.event == 'export')
            {
                $.ajax({
				    url: "http://localhost/diagram/diagrama_script.php",
				    type: "POST",
				    data: {save: 'save', diagrama: msg.data, key},
				}).done(function() {
					saved = true;
					// show a dialog to the user
				    var success = document.getElementById("message");
		        	var content = document.createTextNode("Save with success!");
					success.appendChild(content);
					$("#message").fadeIn(800, function(){
				        window.setTimeout(function(){
				            $('#message').fadeOut('slow');
				        }, 700);
				    });
				}).fail(function(jqXHR, textStatus ) {
				    console.log("Request failed: " + textStatus);
				});
            }
            else if (msg.event == 'save')
            {
                iframe.contentWindow.postMessage(JSON.stringify({action: 'export',
                    format: 'xmlpng', xml: msg.xml, spin: 'Updating page'}), '*');
            }
            // ajax to delete the diagram from database
            else if (msg.event == 'exit')
            {
            	// check if has a image, if not, don't save
            	if (!image && saved == false) {
            		$.ajax({
					    url: "http://localhost/diagram/diagrama_script.php",
					    type: "POST",
					    data: {vazio: 'vazio', diagrama: msg.data, key},
					}).done(function() {
						$("#success").fadeIn(800, function(){
					        window.setTimeout(function(){
					            $('#success').fadeOut('slow');
					        }, 700);
					    });
					}).fail(function(jqXHR, textStatus ) {
					    console.log("Request failed: " + textStatus);
					});
            	}
            	// after delete, you can reload the Home or what u want
                location.href = 'http://localhost/diagram/adianti/Home'; // example
            }
        }
    };

    window.addEventListener('message', receive);
    iframe.setAttribute('src', editor);
    document.body.appendChild(iframe);
};
        
function load()
{
    initial = image;
    start();
};

// get the image tag, because diagram is write like a img
function start()
{
    name = (window.location.hash.length > 1) ? window.location.hash.substring(1) : 'default';
    document.getElementById('image').setAttribute('src', initial);
};

window.addEventListener('hashchange', start);