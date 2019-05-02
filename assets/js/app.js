window.$ = window.jQuery = require('jquery');
window.Popper = require('popper.js').default;
require('bootstrap');

Object.defineProperty (window,'PRODUCT_TYPE_GENERAL',{value : 1});
Object.defineProperty (window,'PRODUCT_TYPE_COMPOSED',{value : 2});
Object.defineProperty (window,'PRODUCT_TYPE_UNUSED',{value : 3});
Object.defineProperty (window,'PRODUCT_TYPE_MENU',{value : 4});
Object.defineProperty (window,'PRODUCT_TYPE_MEAT_ALONE',{value : 5});
Object.defineProperty (window,'PRODUCT_TYPE_COMPOSED_REMOVE',{value : 6});
Object.defineProperty (window,'PRODUCT_TYPE_ONE_MEAT',{value : 7});
Object.defineProperty (window,'PRODUCT_TYPE_TWO_MEAT',{value : 8});
Object.defineProperty (window,'PRODUCT_TYPE_THREE_MEAT',{value : 9});
Object.defineProperty (window,'PRODUCT_TYPE_FOUR_MEAT',{value : 10});
Object.defineProperty (window,'PRODUCT_TYPE_HAMBURGER',{value : 11});
Object.defineProperty (window,'PRODUCT_TYPE_SPECIALITY',{value : 12});
Object.defineProperty (window,'PRODUCT_TYPE_ASSIETTE',{value : 13});

let loading = $('.loading');
let ChildMenu = $('.ChildMenu');
let meat1 = $('.meat1');
let meat2 = $('.meat2');
let meat3 = $('.meat3');
let meat4 = $('.meat4');
let bread = $('.bread');

$('.ProductCreateType').val(1);

$('form[name="product"] .ProductCreateType').change(function() {
    loading.show();
    meat1.css('display', 'none');
    meat2.css('display', 'none');
    meat3.css('display', 'none');
    meat4.css('display', 'none');
    $('.ProductCreate_Custom').css('display', 'none');
    $('.ShowBaking').css('display', 'none');
    let selected = $('.ProductCreateType option:selected').text();

    if (selected == "Ajout immédiat") {
        loading.fadeOut();
    }

    else if (selected == "Composition") {
        loading.fadeOut(500, function() {
            $('.ProductCreate_Default').css('display', 'block');
        });
    }

    else if (selected == "Menu") {
        loading.fadeOut(500, function() {
            $('.ProductCreate_Default').css('display', 'block');
        });
    }

    else if (selected == "Viande") {
        $('.ShowBaking').css('display', 'block');
        loading.fadeOut();
    }

    else if (selected == "Tacos (1 viande)") {
        loading.fadeOut(500, function() {
            $('.ProductCreate_Default').css('display', 'block');
            meat1.css('display', 'block');
        });
    }

    else if (selected == "Tacos (2 viandes)") {
        loading.fadeOut(500, function() {
            $('.ProductCreate_Default').css('display', 'block');
            meat1.css('display', 'block');
            meat2.css('display', 'block');
        });
    }

    else if (selected == "Tacos (3 viandes)") {
        loading.fadeOut(500, function() {
            $('.ProductCreate_Default').css('display', 'block');
            meat1.css('display', 'block');
            meat2.css('display', 'block');
            meat3.css('display', 'block');
        });
    }

    else if (selected == "Tacos (4 viandes)") {
        loading.fadeOut(500, function() {
            $('.ProductCreate_Default').css('display', 'block');
            meat1.css('display', 'block');
            meat2.css('display', 'block');
            meat3.css('display', 'block');
            meat4.css('display', 'block');
        });
    }
});

$(document).on( "click", "#login", function()
{
    $('.loading').show();
    $('.alert').fadeOut().fadeIn(500);
});

$(document).on( "click", "button#addCategorie", function()
{
    $('.loading').show();
});

$(document).on( "click", "button#addProduct", function()
{
    $('.loading').fadeOut(200);
});

$(document).on( "click", "a.delete", function (event)
{
    event.preventDefault();

    let loading = $('.loading');
    loading.show();

    let id = $(this).attr('data-id');
    let type = $(this).attr('data-type');

    if (type === "booking")
    {
        if (confirm('Etes-vous certain de vouloir supprimer la réservation ?'))
        {
            $.ajax({
                method: "POST",
                url: "/Bookings/" + id + "/Delete"
            }).done( function( msg )
            {
                location.reload();
            });
        }
    }
    else if (type === "category")
    {
        if (confirm('Etes-vous certain de vouloir supprimer la catégorie ?'))
        {
            $.ajax({
                method: "POST",
                url: "/Categories/" + id + "/Delete"
            }).done( function( msg )
            {
                location.reload();
            });
        }
    }
    else if (type === "ingredient")
    {
        if (confirm('Etes-vous certain de vouloir supprimer l\'ingrédient ?'))
        {
            $.ajax({
                method: "POST",
                url: "/allingredient/delete/" + id
            }).done( function( msg )
            {
                location.reload();
            });
        }
    }
    else if (type === "product")
    {
        if (confirm('Etes-vous certain de vouloir supprimer le produit ?'))
        {
            $.ajax({
                method: "POST",
                url: "/Products/" + id + "/Delete"
            }).done( function( msg )
            {
                location.reload();
            });
        }
    }
    else if (type === "user")
    {
        if (confirm('Etes-vous certain de vouloir supprimer l\'utilisateur ?'))
        {
            $.ajax({
                method: "POST",
                url: "/Users/" + id + "/Delete"
            }).done( function( msg )
            {
                location.reload();
            });
        }
    }
    loading.hide();
});

$(document).on( "click", "a.orders_reset", function(event)
{
    event.preventDefault();

    $('.modal-title').html('');
    $('.modal-body').html('');

    let loading = $('.loading');

    loading.show();

    if(confirm('Etes-vous certain de vouloir réinitialiser les commandes ?'))
    {
        $.ajax({
            method: "POST",
            url: "/OrdersReset"
        })
            .done(function( msg ) {
                if (msg.status == true)
                {
                    $('.modal-header .fa').removeClass('fa-times').addClass('fa-check');
                    $('.modal-header .icon').css('background', '#82CE34');
                    $('.modal-title').html('Confirmation');
                    $('.modal-body').html('<p class="text-center">Les commandes ont été vidées avec succès.</p>');
                    loading.fadeOut(2500, function() {
                        $('#confirmModal').modal('show');
                    });
                }
                else
                {
                    $('.modal-header .fa').removeClass('fa-check').addClass('fa-times');
                    $('.modal-header .icon').css('background', '#EB1313');
                    $('.modal-title').html('Erreur');
                    $('.modal-body').html('<p class="text-center">Une erreur est survenue.</p>');
                    loading.fadeOut(2500, function() {
                        $('#confirmModal').modal('show');
                    });
                }
            });
    }
    else
    {
        loading.hide();
    }
});

$(document).on( "click", "a.all_reset", function(event)
{
    event.preventDefault();

    let loading = $('.loading');

    loading.show();

    if(confirm('Etes-vous certain de vouloir réinitialiser les tables ?'))
    {
        $.ajax({
            method: "POST",
            url: "/AllReset"
        })
            .done(function( msg )
            {
                location.reload();
            });
    }
    loading.hide();
});

$('.OrderFinish').on("click", function() {
    let borneID = $(this).attr('data-borne_id');
    let id = $(this).attr('data-order_id');

    if (borneID == 5) {
        if (confirm('Le client a payé la commande ?'))
        {
            $.ajax({
                type: "POST",
                url: "/Orders/"+id+"/SetFinish"
            }).done(function() {
                location.reload();
            });
        }
    } else {
        if (confirm('Etes-vous certain de vouloir terminer cette commande ?'))
            $.ajax({
                type: "POST",
                url: "/Orders/"+id+"/SetFinish"
            }).done(function() {
                location.reload();
            });
    }
});

$('.Print').on("click", function() {
    let order_id = $(this).attr('data-order_id');
    let popup = window.open( '/GetReceipt/'+order_id, '_blank', 'location=no,resizable=no,status=no,toolbar=no,top=0,left=0,height=100%,width=auto');
    popup.addEventListener('load', function(){
        popup.print();
        popup.close();
    }, true);
    let popup2 = window.open( '/GetReceipt/'+order_id, '_blank', 'location=no,resizable=no,status=no,toolbar=no,top=0,left=0,height=100%,width=auto');
    popup2.addEventListener('load', function(){
        popup2.print();
        popup2.close();
    }, true);
});

$('.RemoveAll').on('click', function() {
    if (confirm('Etes-vous certain de vouloir supprimer tous les ingrédients de cette composition ?'))
    {
        let checked = [];

        $(':checkbox:checked').each(function(i) {
            checked[i] = $(this).attr('data-ingredient_id');
        });

        $.ajax({
            type: "POST",
            url: "/Ajax/RemoveSelectedIngredientInProduct",
            data: {id: checked}
        }).done(function(msg) {

        });
    }
});

function refresh_Orders() {
    let route = $('.twig_vars').attr('data-current_route');
    if (route == "Admin_GetAllOrders")
    {
        loading.show();
        location.reload();
    }
}

$(function() {
    let time = 5;
    let decreaseInterval;
    let intervalTime = 1000;

    function decrease() {
        if (time > 0) time--;
        if (time == 0) {
            refresh_Orders();
        }
    }

    $('.js-datepicker').datepicker({
        format: 'mm/dd/yyyy',
        todayHighlight: true
    });

    decreaseInterval = setInterval(decrease, intervalTime);

    $(document).on('mousemove', function() {
        clearInterval(decreaseInterval);
        time = 5;

        decreaseInterval = setInterval(decrease, intervalTime);
    });
});
