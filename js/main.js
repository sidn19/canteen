// data
const itemGroups = [
    {
        id: 1,
        name: 'Sandwiches',
        position: 1,
        items: [
            {
                id: 1,
                name: 'Butter and Cheese Sandwich',
                image: './images/butter-cheese.jpg',
                price: 30,
                position: 1,
                rating: 3
            },
            {
                id: 2,
                name: 'Jam Sandwich',
                image: './images/jam.jpg',
                price: 20,
                position: 2,
                rating: 1
            },
            {
                id: 3,
                name: 'Vegetable Sandwich',
                image: './images/Vegetable-Sandwich.jpg',
                price: 30,
                position: 3,
                rating: 3
            },
            {
                id: 4,
                name: 'Vegetable and Cheese Sandwich',
                image: './images/veg-cheese.jpg',
                price: 40,
                position: 4,
                rating: 4
            },
            {
                id: 5,
                name: 'Grilled Veg. Sandwich',
                image: './images/grilled-veg.jpg',
                price: 50,
                position: 5,
                rating: 2
            },
            {
                id: 6,
                name: 'Egg Sandwich',
                image: './images/egg.jpg',
                price: 40,
                position: 6,
                rating: 5
            }
        ]
    },
    {
        id: 2,
        name: 'Breakfast',
        position: 2,
        items: [
            {
                id: 7,
                name: 'Upma',
                image: './images/Rava-Upma.jpg',
                price: 30,
                position: 1,
                rating: 1
            },
            {
                id: 8,
                name: 'Poha',
                image: './images/Poha-Recipe.jpg',
                price: 20,
                position: 2,
                rating: 3
            },
            {
                id: 9,
                name: 'Misal Pav',
                image: './images/misal-pav.jpg',
                price: 30,
                position: 3,
                rating: 4
            },
            {
                id: 10,
                name: 'Idli',
                image: './images/idli.jpg',
                price: 30,
                position: 4,
                rating: 3
            },
            {
                id: 11,
                name: 'Medu Vada',
                image: './images/medu-vada.jpg',
                price: 30,
                position: 5,
                rating: 2
            }
        ]
    },
    {
        id: 3,
        name: 'Lunch',
        position: 3,
        items: [
            {
                id: 12,
                name: 'Veg. Fried Rice',
                image: './images/veg-fried-rice-recipe-1.jpg',
                price: 60,
                position: 1,
                rating: 4
            },
            {
                id: 13,
                name: 'Chi. Fried Rice',
                image: './images/chickenfriedrice-10.jpg',
                price: 80,
                position: 2,
                rating: 5
            },
            {
                id: 14,
                name: 'Veg. Noodles',
                image: './images/veg-noodles-recipe-1.jpg',
                price: 65,
                position: 3,
                rating: 2
            },
            {
                id: 15,
                name: 'Chi. Noodles',
                image: './images/SCHEZWAN-CHICKEN-NOODLES.jpg',
                price: 90,
                position: 4,
                rating: 3
            },
            {
                id: 16,
                name: 'Chapati Bhaji',
                image: './images/chapati.jpg',
                price: 40,
                position: 5,
                rating: 5
            }
        ]
    }
];

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

let balance = 100;

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
            $('.logged-in-content').show();
            $('.logged-out-content').hide();
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
                        <img src="${item.image}">
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

    $('#balance').text(balance);

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
});