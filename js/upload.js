(function(){
  var 
  formdata = null,
  fileField = $("#fileToUpload")[0],
  titleField = $("#fileTitle")[0],
  fileContent= null,
  fileName = null,
  title = null;
    
  if (window.FormData) {
    formdata = new FormData();
  }
    
  if (fileField.addEventListener) {
      fileField.addEventListener("change", function (evt) {
          $("#response").html("");
          fileContent = this.files[0];
          
          //check if it is an image
          var isImage = false;
          var header = "";
          var fileReader = new FileReader();
          fileReader.onloadend = function(e) {
              var arr = (new Uint8Array(e.target.result)).subarray(0, 4);
              for(var i = 0; i < arr.length; i++) {
                  header += arr[i].toString(16);
              }
              switch (header) {
                  case "89504e47"://type = "image/png";
                      isImage = true;
                      break;
                  case "47494638": //type = "image/gif";
                      isImage = true;
                      break;
                  case "ffd8ffe0":
                  case "ffd8ffe1":
                  case "ffd8ffe2"://type = "image/jpeg";
                      isImage = true;
                      break;
                  default: //type = "unknown";
                      isImage = false;
                      break;
              }
              // Check the file signature against known type
              if (isImage||true) {
                  uploadImage();
              } else {
                  $("#response").html("File is not an image!"); 
              }
          };
          fileReader.readAsArrayBuffer(fileContent);
      }, false);
  };
  function uploadImage(){
      fileName = fileContent.name;
      title = titleField.value;
      if (formdata) {
          formdata.append(titleField.name,title);//name="" value="30000"
          formdata.append("MAX_FILE_SIZE", "20971520");
          formdata.append(fileField.name,fileContent,fileName);
          $.ajax({
              url: "upload.php",
              type: "POST",
              data: formdata,
              processData: false,
              contentType: false, 
              dataType: "json",
              success: function(res){
//                  alert(res["uploadOk"]);
//                  alert(res["error"]);
//                  alert(res["text"]);
//                  alert(res["archive"]["filename"]);
//                  alert(res["archive"]["title"]);
                  processResponse(res);
              }
          });
          formdata = new FormData();
          $("#dataForm")[0].reset();
      }
  };
  function processResponse(resp){
      if(resp["uploadOk"]){
          showUploadedItem(resp["archive"]["filename"],resp["archive"]["title"]);
          $("#response").html(resp["text"]); 
      } else {
          $("#response").html(resp["text"]); 
      }
  };
})();

function showUploadedItem (imageName,imageTitle) {
  var sectionPost = $("#posts"),
      article   = document.createElement("article"),
      h2   = document.createElement("h2"),
      h2   = document.createElement("h2"),
      img  = document.createElement("img");
    h2.innerHTML = imageTitle;
    img.src = "images/"+imageName;
    img.alt = imageTitle;
    article.appendChild(h2);
    article.appendChild(img);
    sectionPost.prepend(article);
};

(function() {
    setInterval(function() {
        $.ajax({
            url: 'getHits.php',
            dataType: 'json',
            success: function(res){
                $('#counterPosts').html(res["count"]);
            }
        });
    }, 15000);
})();