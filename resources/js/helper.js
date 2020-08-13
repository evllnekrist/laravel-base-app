let ajax_headers = { 
    'X-CSRF-TOKEN': "{{ csrf_token() }}" 
};

function triggerModal(id, params = null){
    $("#"+id+"_form")[0].reset();
    if(params){
        var btn = $("#"+id+"_btn");
        jQuery.each(params, function(index,value){ //turn the params into datas attribute
            // console.log(index,value);
            btn.data(index,value);
            btn.click();
        });
    }else{
        $("#"+id+"_btn").trigger("click");
    }
}

function displayElement(id_selected,id_opposite=null){
    $("#"+id_selected).show();
    id_opposite ? $("#"+id_opposite).hide() : '';
    
}

function undisplayElement(id_selected,id_opposite=null){
    $("#"+id_selected).hide();
    id_opposite ? $("#"+id_opposite).show() : '';
}

function base64Splitter(base64Content) { // https://stackoverflow.com/questions/48456149/how-to-detect-the-mime-type-of-data-url
    return base64Content.split(",");
}

function detectMimeType(base64Content) { 
    base64ContentArray = base64Splitter(base64Content);
    return base64ContentArray[0].match(/[^:\s*]\w+\/[\w-+\d.]+(?=[;| ])/)[0]; // mime type
}

function detectBase64Header(base64Content) { 
    base64ContentArray = base64Splitter(base64Content);
    return base64ContentArray[0].replace(/\s+/g, '');; // base 64 data
}

function detectBase64Data(base64Content) { 
    base64ContentArray = base64Splitter(base64Content);
    return base64ContentArray[1]; // base 64 data
}

function makeBlob(dataURL) { // https://stackoverflow.com/questions/16245767/creating-a-blob-from-a-base64-string-in-javascript/16245768#16245768
    const BASE64_MARKER = ';base64,';
    const parts = dataURL.split(BASE64_MARKER);
    const contentType = parts[0].split(':')[1];
    const raw = window.atob(parts[1]);
    const rawLength = raw.length;
    const uInt8Array = new Uint8Array(rawLength);

    for (let i = 0; i < rawLength; ++i) {
        uInt8Array[i] = raw.charCodeAt(i);
    }

    return new Blob([uInt8Array], { type: contentType });
}

// SELECT distinct(PARSENAME(FileName, 1)) from dbo.BPI_FolderAndFile
var extensions = {
    "text/html":                             "html",
    // "text/css":                              "css",
    "text/xml":                              "xml",
    // "image/gif":                             "gif",
    "image/jpg":                             "jpg",
    "image/jpeg":                            "jpeg",
    // "application/x-javascript":              "js",
    // "application/atom+xml":                  "atom",
    // "application/rss+xml":                   "rss",
    // "text/mathml":                           "mml",
    "text/plain":                            "txt",
    // "text/vnd.sun.j2me.app-descriptor":      "jad",
    // "text/vnd.wap.wml":                      "wml",
    // "text/x-component":                      "htc",
    "image/png":                             "png",
    // "image/tiff":                            "tiff",
    // "image/vnd.wap.wbmp":                    "wbmp",
    // "image/x-icon":                          "ico",
    // "image/x-jng":                           "jng",
    // "image/x-ms-bmp":                        "bmp",
    // "image/svg+xml":                         "svg",
    // "image/webp":                            "webp",
    // "application/java-archive":              "jar",
    // "application/mac-binhex40":              "hqx",
    "application/msword":                    "doc",
    "application/pdf":                       "pdf",
    // "application/postscript":                "ps",
    // "application/rtf":                       "rtf",
    "application/vnd.ms-excel":              "xls",
    "application/vnd.ms-powerpoint":         "ppt",
    "application/vnd.ms-access":             "mdb",
    // "application/vnd.wap.wmlc ":             "wmlc",
    // "application/vnd.google-earth.kml+xml":  "kml",
    // "application/vnd.google-earth.kmz":      "kmz",
    // "application/x-7z-compressed":           "7z",
    // "application/x-cocoa":                   "cco",
    // "application/x-java-archive-diff":       "jardiff",
    // "application/x-java-jnlp-file":          "jnlp",
    // "application/x-makeself":                "run",
    // "application/x-perl":                    "pl",
    // "application/x-pilot":                   "prc",
    // "application/x-rar-compressed":          "rar",
    // "application/x-redhat-package-manager":  "rpm",
    // "application/x-sea":                     "sea",
    // "application/x-shockwave-flash":         "swf",
    // "application/x-stuffit":                 "sit",
    // "application/x-tcl":                     "tcl",
    // "application/x-x509-ca-cert":            "der",
    // "application/x-xpinstall":               "xpi",
    "application/xhtml+xml":                 "xhtml",
    // "application/zip":                       "zip",
    // "application/octet-stream":              "bin",
    // "application/octet-stream":              "deb",
    // "application/octet-stream":              "dmg",
    // "application/octet-stream":              "eot",
    // "application/octet-stream":              "iso",
    // "application/octet-stream":              "msi",
    "application/vnd.oasis.opendocument.text":                  "odt",
    "application/vnd.oasis.opendocument.spreadsheet":           "ods",
    "application/vnd.oasis.opendocument.presentation":          "odp",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document":            "docx",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":                  "xlsx",
    "application/vnd.openxmlformats-officedocument.presentationml.presentation":          "pptx",
    // "audio/midi":                            "mid",
    // "audio/mpeg":                            "mp3",
    // "audio/ogg":                             "ogg",
    // "audio/x-realaudio":                     "ra",
    // "video/3gpp":                            "3gpp",
    // "video/mpeg":                            "mpg",
    // "video/quicktime":                       "mov",
    // "video/x-flv":                           "flv",
    // "video/x-mng":                           "mng",
    // "video/x-ms-asf":                        "asx",
    // "video/x-ms-wmv":                        "wmv",
    // "video/x-msvideo":                       "avi",
    // "video/mp4":                             "m4v;
};

function detectExtType(mimeType) { 
    for (var key in extensions) {
        // console.log(key == mimeType,key,mimeType,extensions[key]);
        if (key == mimeType) {
            return extensions[key]
        }
    }
    return 'pdf';
}

function filenameByDate(){
    var currentdate = new Date(); 
    return  currentdate.getFullYear() + "-"  
            + (currentdate.getMonth()+1)  + "-"
            + currentdate.getDate() + "_"
            + currentdate.getHours() + "-"  
            + currentdate.getMinutes() + "-" 
            + currentdate.getSeconds();
}