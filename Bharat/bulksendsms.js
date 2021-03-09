document.addEventListener('DOMContentLoaded', async function () {
    var lead_id = "";
    var user_id = "";
    var modulename = "";
    var recdata_1 ="";
    ZOHO.embeddedApp.on("PageLoad", async function (data) {
        console.log("Lead Record");
        console.log(data);
        $.lead_id_list=data.EntityId;
        modulename = data.Entity;
        console.log($.lead_id_list);
        ZOHO.CRM.API.getAllRecords({Entity:"Sms_Templates",sort_order:"asc",per_page:50,page:1})
        .then(function(tempdata){
            tempname = tempdata.data;
            console.log("Template Name");
            console.log(tempname);
            template_list = [];
            for (var i = 0, l = tempname.length; i < l; i++) {
                temp_id = tempname[i].id;
                var template_object = new Object();
                template_object.id = tempname[i].id;
                template_object.title = tempname[i].Name;
                template_object.temp_id =tempname[i].Message;
                template_list.push(template_object)
            }
			template_list.sort(function(currentele,nextele)
			{
				console.log('CurrentEle ' , currentele, 'Next Eklle ' , nextele);
				currentname=currentele.title;
				nextname=nextele.title;
				console.log('CurrentName ' , currentname, 'Next Name ' , nextname);
				return currentname.toLowerCase().localeCompare(nextname.toLowerCase());
			});
            console.log("Template List");
            console.log(template_list);
            $( document ).ready(function() {
             var elm = document.getElementById('template_option'), // get the select
               df = document.createDocumentFragment(); // create a document fragment to hold the options while we create them
               for (var i = 0, l = template_list.length; i < l; i++) {
               // loop, i like 42.
               var option = document.createElement('option'); // create the option element
               option.value = template_list[i].id; // set the value property
               option.appendChild(document.createTextNode(template_list[i].title)); // set the textContent in a safe way.
               df.appendChild(option); // append the option to the document fragment
           }
             elm.appendChild(df); // append the document fragment to the DOM. this is the better way rather than setting innerHTML a bunch of times (or even once with a long string)
             // Example call of 'refresh'
             $('.selectpicker').selectpicker('refresh');
			 $('#template_option').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
				 console.log("IsSelected " , isSelected , " clickedIndex" , clickedIndex , 'perivous value ' , previousValue);

			});

             $("#template_option").change(function() {
				 console.log('On Change Event Called');
             $.template_id = ($("#template_option").val());
             ZOHO.CRM.API.getRecord({Entity:"Sms_Templates",RecordID:$.template_id})
             .then(function(template){
                 console.log("Template Detail");
                 $.message = template.data[0].Message;      
                 $("#message").val($.message);
                 console.log($.message);     
             })
          });
             
           });
        })
        $("#sendsms").click(function () {
            console.log("Button CLick trigger");
			spinner.style.display='block';
            var func_name = "send_bulk_sms_to_lead";
            var paramas ={
             "arguments": JSON.stringify({
                  "lead_id_list":$.lead_id_list,
                  "messagecontent" : $("#message").val()
              }) 
            };
            ZOHO.CRM.FUNCTIONS.execute(func_name, paramas)
            .then(function(data){
               // console.log("data come");
                console.log('response data --> ',data);
                //datas = Get_function_response.details.output;
                //console.log(datas);
				 if(data.code=='success'){
					 spinner.style.display = 'none'; 
					   successAlert.style.display = 'block'; 
					   errorAlert.style.display = 'none'; 
					 }
					 else{
					 spinner.style.display = 'none'; 
					 errorAlert.style.display = 'block'; 
					 sendsms.disabled = false;
					errorAlert.innerHTML = "Unable to send sms due to error "+data.message;
					}
                // $('#Createnotewindow').remove();
                // if(datas=="success")
                // {
                //         Responsemessage="SMS Sent Successfully";  
                // }

                // $('#newpage').append('<div class="container-fluid_1"><div class="row"><div class="col-12" style="margin-top: 180px;margin-left: 230px;"><div class="col-4 alert-box"><hr><p>'+Responsemessage+'</p><hr><div><button style="margin-left: 80%;margin-bottom: 3%;" type="button" class="btn btn-sm btn-primary" onclick="popupclose()">Close</button></div></div></div></div></div>');
            })
        });
    });             
    ZOHO.embeddedApp.init();
})