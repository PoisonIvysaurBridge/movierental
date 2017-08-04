/**
 * Created by drjeoffreycruzada on 12/07/2017.
 */


function computeTotalPrice(){
    var totalprice = 0;
    $(".orderItem").each(function(){
        var id = $(this).attr("myId");
        var price = parseFloat($(".menuorders .price-id-"+id).html());

        totalprice+=price;
    });
    return totalprice;
}
function updatePrice(){
    var totalprice = computeTotalPrice();
    $(".totalprice").empty();
    if(totalprice != 0){
        $(".totalprice").append(totalprice);
    }
}
$(document).ready(function(){

    $('.addToCart').click(function()
    {
        //When the cashier confirms to add menu item to the list of orders
        var mydesc = $(this).attr("myDesc");
        var id = $(this).attr("myId");
        var price = $(this).attr("myPrice");
        $.ajax({
            url: "ajaxresponse/CartAdd.php",
            type:'POST',
            data:
                {
                    id: $(this).attr('myId')
                },
            success: function(data)
            {
                if(data.exists == false){
                    $(".menuorders").append('<div class = "orderItem w3-col m12 w3-blue order-id-'+id+'" myId = '+id+'>'+
                        '<button type="button" class="btn btn-danger col-s-2 pull-left removeOrderItem" myId = "'+id+'" style = "border-radius: 0px;" onclick = "RemoveOrderedItem(\''+id+'\')">X</button>'+
                        '<div class = "col-s-2 pull-left w3-green count-id-'+id+'" style = "padding: 6px 12px;">1</div>'+
                        '<button type="button" class="btn btn-warning col-s-2 pull-left" style = "border-radius: 0px;" onclick = "AddOrderedItem(\''+id+'\','+price+')">+</button>'+
                        '<button type="button" class="btn btn-warning col-s-2 pull-left" style = "border-radius: 0px;" onclick = "SubtractOrderedItem(\''+id+'\','+price+')">-</button>'+
                        '<div div class = "col-s-4">'+
                        '<div class = "w3-blue pull-left" style = "padding: 6px 12px;">'+mydesc+'</div>'+
                    '<div class = "w3-orange pull-right price-id-'+id+'" style = "padding: 6px 12px;width:65px;text-align: right;">'+price+'</div>'+
                        '</div>'+
                        '</div>')
                }
                else{
                    $(".menuorders .count-id-"+id).empty().append(data.counting);
                    $(".menuorders .price-id-"+id).empty().append((price*parseInt(data.counting)));
                }
                updatePrice();

            }
        });
    });
    $("#discountsel").on('input',function(){
       if($(this).val() == "Others"){
           $("#discountID").css({
               "display":"block"
           })
           $("#discountRate").css({
               "display":"block"
           });
           $("#discountID").attr({
              "placeholder": "Enter reason for discount"
           });
       }
       else if($(this).val() == "None"){
           updatePrice();
           var totalprice = computeTotalPrice();
           var recieved = parseFloat($("#amountrecieved").val());
           if(recieved > 0){
               var change = recieved -(totalprice);
               $(".changeprice").empty().append(change);
           }
           $("#discountID").css({
               "display":"None"
           })
           $("#discountRate").css({
               "display":"None"
           });
       }
       else{
           var dr = 0.2;
           var totalprice = computeTotalPrice();
           var recieved = parseFloat($("#amountrecieved").val());
           $(".totalprice").empty();
           if(totalprice != 0){
               $(".totalprice").append(totalprice - totalprice*dr);
           }
           if(recieved > 0){
               var change = recieved -(totalprice-totalprice*dr);
               $(".changeprice").empty().append(change);
           }
           $("#discountID").css({
               "display":"block"
           })
           $("#discountRate").css({
               "display":"None"
           });
           $("#discountID").attr({
               "placeholder": "Enter I.D."
           });
       }
    });
    $("#amountrecieved").on('input',function(){
        var dr = 0;
        if($("#discountsel").val() == "Others")
        {
            var dr = parseFloat($("#discountRate").val())/100;
            if(isNaN(dr)){
                dr = 0;
            }

        }
        else if($("#discountsel").val() != "None"){
            dr = 0.2;
        }
       var totalprice = parseFloat(computeTotalPrice());
       var recieved = parseFloat($(this).val());
       var change = recieved-(totalprice-totalprice*dr);
       $(".changeprice").empty().append(change);
    });
    $("#discountRate").on('input',function(){
        var dr = parseFloat($(this).val())/100;
        if(dr == NaN){
            dr = 0;
        }
        var totalprice = parseFloat(computeTotalPrice());
        var recieved = parseFloat($("#amountrecieved").val());
        if(totalprice > 0){
            $(".totalprice").empty().append((totalprice-totalprice*dr));
        }
        if(recieved > 0){
            var change = recieved -(totalprice-totalprice*dr);
            $(".changeprice").empty().append(change);
        }
    });
});

function RemoveOrderedItem(id){
    var id = id;
    $.ajax({
        url: "ajaxresponse/ItemRemove.php",
        type: "POST",
        data:{
            id: id
        },
        success: function(data){
            $(".order-id-"+id).remove();
            updatePrice();
        }
    });
}

function SubtractOrderedItem(id,price){
    var id = id;
    var price = parseFloat(price);
    $.ajax({
        url: "ajaxresponse/SubtractOrder.php",
        type: "POST",
        data:{
            id: id
        },
        success: function(data){
            if(data.counts == 0) {
                $(".order-id-" + id).remove();
            }
            else{
                $(".menuorders .count-id-"+id).empty().append(data.counts);
                $(".menuorders .price-id-"+id).empty().append((price*data.counts));
            }
            updatePrice();
        }
    });
}

function AddOrderedItem(id,price){
    var id = id;
    $.ajax({
        url: "ajaxresponse/AddOrder.php",
        type: "POST",
        data:{
            id: id
        },
        success: function(data){
            $(".menuorders .count-id-"+id).empty().append(data.counts);
            $(".menuorders .price-id-"+id).empty().append((price*data.counts));
            updatePrice();
        }

    });
}