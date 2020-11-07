if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register(`${location.pathname}serviceWorker.js`);
    });
}

const apiUrl = '/api';

let orders = [
    {
        id: 1,
        items: [
            {
                id: 14,
                rating: null,
                name: 'Veg. Noodles'
            },
            {
                id: 13,
                rating: null,
                name: 'Chi. Noodles'
            }
        ],
        price: 145,
        status: 'Delivered'
    }
];

let balance = 0;

let googleUser = null;
gapi.load('auth2', () => {
    const auth2 = gapi.auth2.init({
        client_id: '570178535400-0ljjrn2urq7el0maauibd1qjq0482n76.apps.googleusercontent.com',
        hosted_domain: 'student.mes.ac.in'        
    });

    auth2.attachClickHandler(document.getElementById('loginButton'), {});
    auth2.attachClickHandler(document.getElementById('loginButton2'), {});

    auth2.currentUser.listen(user => {
        if (user && user.isSignedIn()) {
            googleUser = user;
            fetch(`${apiUrl}/users?token=${googleUser.getAuthResponse().id_token}`)
                .then(response => response.json())
                .then(data => {
                    balance = data.balance;
                    $('#balance').text(balance);
                    $('.logged-in-content').show();
                    $('.logged-out-content').hide();
                })
                .catch(error => {
                    console.error(error);
                });
        }
        else {
            googleUser = null;
            $('.logged-in-content').hide();
            $('.logged-out-content').show();
        }
    });
});


$(document).ready(() => {
    let cart = [];
    let itemGroups = [];

    fetch(`${apiUrl}/menu`)
        .then(response => response.json())
        .then(items => {
            $('.loader').hide();
            $('.loader-container').hide();
            itemGroups = items;
            itemGroups.forEach((itemGroup, index) => {
                $('#menuCard').append(
                    `<div class="category-name" id="${itemGroup.name}">
                        ${itemGroup.name}
                    </div>`
                );
        
                itemGroup.items.forEach(item => {
                    $('#menuCard').append(
                        `<div class="flex-container">
                            <div class="inner-flex-container">
                                <img src="${apiUrl + item.image}">
                                <div class="category-item">
                                    <div>${item.name}</div>
                                    <div class="inner-flex-container category-info-block">
                                        <div>&#8377;${item.price}</div>
                                        <div class="rating">[${item.rating}&starf;]</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button title="Add to Cart" class="add-to-cart" data-item-id="${item.id}" data-item-group-id="${itemGroup.id}">
                                    Add
                                </button>
                            </div>
                        </div>`
                    );
                });
        
                if (index !== itemGroups.length - 1) {
                    $('#menuCard').append(
                        `<hr>`
                    );
                }
                
                $('#shortMenuCard').append(
                    `<div>
                        <a href="#${itemGroup.name}">${itemGroup.name}</a>
                    </div>`
                );
            });
        });

    $('#menuCard').on('click', 'button.add-to-cart', event => {
        const itemId = parseInt($(event.target).attr('data-item-id'));
        const itemGroupId = parseInt($(event.target).attr('data-item-group-id'));

        const itemGroup = itemGroups.find(x => x.id === itemGroupId);
        const item = itemGroup.items.find(x => x.id === itemId);

        const totalCost = parseInt($('.total-cost').text().slice(0, $('.total-cost').text().length / 2)) + item.price;
        const totalItems = parseInt($('.total-items').text().slice(0, $('.total-items').text().length / 2)) + 1;

        $('.total-cost').text(totalCost);
        $('.total-items').text(totalItems);

        $('#cartItems').append(
            `<tr>
                <td>${item.name}</td>
                <td style="width: 4rem; text-align: center;">&#8377;${item.price}</td>
                <td>
                    <button data-item-id="${item.id}" class="discard-from-cart">Discard</button>
                </td>
            </tr>`
        );
        cart.push(item);
        $('#viewCartButton').attr('disabled', false);

        if (totalCost > balance) {
            $('#confirmOrderButton').attr('disabled', true);
        }
        else {
            $('#confirmOrderButton').attr('disabled', false);
        }
    });

    $('#viewCartButton').click(() => {
        $('.blurable').css('filter', 'blur(5px)');
        $('#cartModal').css('display', 'flex');
    });

    $('.modal, .close-button').click(event => {
        if (event.target.id === 'cartModal' || $(event.target).hasClass('close-button')) {
            $('.blurable').css('filter', 'none');
            $('#cartModal').css('display', 'none');
        }
    });

    $('#cartModal').on('click', 'button.discard-from-cart', event => {
        const itemId = parseInt($(event.target).attr('data-item-id'));
        const itemIndex = cart.findIndex(x => x.id === itemId);

        const totalCost = parseInt($('.total-cost').text().slice(0, $('.total-cost').text().length / 2)) - cart[itemIndex].price;
        const totalItems = parseInt($('.total-items').text().slice(0, $('.total-items').text().length / 2)) - 1;

        $('.total-cost').text(totalCost);
        $('.total-items').text(totalItems);

        cart.splice(itemIndex, 1);

        $(event.target).parent().parent().remove();

        if (totalCost <= balance) {
            $('#confirmOrderButton').attr('disabled', false);
        }

        if (cart.length === 0) {
            $('.blurable').css('filter', 'none');
            $('#cartModal').css('display', 'none');
            $('#viewCartButton').attr('disabled', true);
        }
    });

    $('#logoutButton').click(() => {
        const auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut();
    });

    $('#confirmOrderButton').click(() => {
        fetch(`${apiUrl}/orders`, {
            method: 'POST',
            body: new URLSearchParams({
                cart: JSON.stringify(cart.map(x => x.id)),
                token: googleUser.getAuthResponse().id_token
            })
        })
            .then(response => response.json())
            .then(data => {
                balance = data.balance;
                cart = [];
                $('#cartItems').children().remove();
                $('.total-cost').text('0');
                $('.total-items').text('0');
                $('#balance').text(balance);
                $('.blurable').css('filter', 'none');
                $('#cartModal').css('display', 'none');
            });
    });
});