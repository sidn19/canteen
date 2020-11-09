if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register(`${location.pathname}serviceWorker.js`);
    });
}

const apiUrl = '/api';

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
            $('.user-name').text(user.getBasicProfile().getGivenName());

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
    let feedbackItems = [];

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
        if (
            event.target.id === 'cartModal' || event.target.id === 'ordersModal' || 
            event.target.id === 'feedbackModal' || event.target.id === 'thankYouModal' || 
            $(event.target).hasClass('close-button')
        ) {
            $('.blurable').css('filter', 'none');
            $('.modal').css('display', 'none');
            $('#ordersContent').html('');
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
                $('#viewCartButton').attr('disabled', true);
                $('.blurable').css('filter', 'none');
                $('#cartModal').css('display', 'none');
            });
    });

    $('#ordersButton, .back-arrow').click(() => {        
        $('.orders-loader').show();
        $('#feedbackModal').css('display', 'none');
        $('#ordersModal').css('display', 'flex');

        fetch(`${apiUrl}/orders?token=${googleUser.getAuthResponse().id_token}`)
            .then(response => response.json())
            .then(orders => {                
                $('.orders-loader').hide();
                if (orders.length === 0) {
                    $('#ordersContent').html(`
                        <p style="text-align: justify;">
                            No orders were found. You can begin ordering by adding items to your cart and confirming the order.
                        </p>
                    `);
                }
                else {
                    $('#ordersContent').html('');
                    orders.forEach(order => {
                        const date = new Date(order.createdAt);
                        $('#ordersContent').append(`
                            <div style="display: flex; flex-direction: column; margin: 1.2rem 0rem;">
                                <div style="display: flex; flex-direction: row; justify-content: space-between;">
                                    <div>${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}, ${date.getHours() % 12}:${date.getMinutes()} ${date.getHours() >= 12 ? 'PM' : 'AM'}</div>
                                    <div><strong>${order.status}</strong></div>
                                </div>
                                <div style="font-size: 0.9rem; display: flex; flex-direction: row;">
                                    <div>Items:</div>
                                    <div style="padding-left: 0.3rem">${order.items.map(x => x.name).join(', ')}</div>
                                </div>
                                <div style="font-size: 0.9rem; display: flex; flex-direction: row; justify-content: space-between; margin-top: 0.4rem;">
                                    <div>
                                        <button class="small-button feedback-button" data-items='${JSON.stringify(order.items)}'>Post Feedback</button>
                                    </div>
                                    <div>Amount: &#8377;${order.amount}</div>
                                </div>
                            </div>
                        `);
                    });
                }
            });
    });

    $('#ordersModal').on('click', 'button.feedback-button', event => {
        feedbackItems = JSON.parse($(event.target).attr('data-items'));
        $('#feedbackContent').html('');
        feedbackItems.forEach(item => {
            $('#feedbackContent').append(`
                <div style="display: flex; flex-direction: column; margin: 1rem 0rem;">
                    <label for="${item.itemId}-review">${item.name}</label>
                    <input type="range" id="${item.itemId}-score" min="1" max="5">
                    <textarea style="height: 4rem;" id="${item.itemId}-review" placeholder="Enter review..."></textarea>
                </div>
            `);
        });
        $('#ordersContent').html('');
        $('#ordersModal').css('display', 'none');
        $('#feedbackModal').css('display', 'flex');
    });

    $('#feedbackButton').click(event => {
        event.preventDefault();

        // get input
        const reviews = [];
        feedbackItems.forEach(item => {
            reviews.push({
                itemId: item.itemId,
                score: parseInt($(`#${item.itemId}-score`).val()),
                review: $(`#${item.itemId}-review`).val()
            });
        });

        fetch(`${apiUrl}/feedback`, {
            method: 'POST',
            body: new URLSearchParams({
                reviews: JSON.stringify(reviews),
                token: googleUser.getAuthResponse().id_token
            })
        });

        $('#feedbackModal').css('display', 'none');
        $('#thankYouModal').css('display', 'flex');
    });
});