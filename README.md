Online College Canteen System is a project aimed at ordering food items from college canteen online without a need of standing in long queues.
Students need to deposit an amount in the college canteen which will be updated as Virtual Money in the database which can be used at the time of ordering.
This project has 3 views namely Admin view, Student view and Metrics view.


**Report Link: https://drive.google.com/file/d/1wWz4tiB6siyYE0HnUM7LQPOKp6J68k_n/view?usp=sharing**



***Introduction***

The occurrence of a large queue in the canteen during busy hours leads to a lot of chaos. This is a  bigger problem in recent times due to the Coronavirus Pandemic, as many government institutions have called for social distancing. We intend to solve this problem by using a virtual  queue and thus avoiding the gathering of people in these two different cases namely the ordering  of food and the collection of the ordered food. To counter this a virtual currency system can be  developed to make the flow easier. This system allows us to require students to only come to the  canteen counter once to make the physical cash transaction and from then on, the student’s can  simply place their order and collect it when they’re notified that their order is ready through the application.



***System Requirements***

The following software are required to be able to run the web application through a web server:
1. PHP 7.4
2. MySQL 5.6
3. Apache 2
4. Git
5. Let’s Encrypt
6. Composer



***Technologies Used***

1. HTML5
2. CSS3
3. JavaScript
4. Ajax
5. JSON
6. PHP
7. MySQL
8. PHP Mailer
9. Google Authentication



***Project Working with Snapshots:***

**1. Student View:**
Only valid MES ID is allowed through Google Authentication. Students can select the food  items that they want to order and then proceed to payment. The payment shall be done using the  virtual money stored in the student’s wallet. The students can view their transaction history that  contains deductions or deposits to their wallet and thus, one can keep track of their spendings.

1)Selecting Items/Ordering:
You can add items and select how much quantity you want. Click on All and then View Cart.

![image14](https://user-images.githubusercontent.com/54242817/127758023-26490ad0-f5b2-4830-92d8-696d7f8a33ad.png)

Canteen Menu

You can add items and select how much quantity you want. Click on All and then View Cart.

2) When you click on View cart You Will be redirected to the Login Page here you need to login only through your MES Email id only.

![image13](https://user-images.githubusercontent.com/54242817/127758021-06401f7d-e151-4bf4-8ceb-d6ee04acfe1f.png)

Cart and Login

3) After successful login you can see the Confirm Order button and your virtual balance below that here you can place your orders.

![image11](https://user-images.githubusercontent.com/54242817/127758018-a5b3b530-2b65-4a9b-b737-107a9fee7118.png)

Cart and Confirm Order after Login

4) Email notifications are used here to reduce the queues. Students are notified whether their orders are Accepted, Rejected or are ready through email. By which we can avoid the chaos in  the Canteen place.

![image6](https://user-images.githubusercontent.com/54242817/127758012-c9bf8864-db29-4f03-bcfd-fc1aef0f54d4.png)

Email Confirmation

5) We Review orders or history in the past and we can get the exact date,time, status in this tab. We can also give reviews to the specific order if we think we should let know the canteen admin.

![image4](https://user-images.githubusercontent.com/54242817/127758010-ea4972c5-e7af-4c1f-89f2-dc223a378d05.png)

Order Review

6) If we want to give the feedback then click on Post Feedback option provided in the order history then you will be redirected to the feedback form.
You can rate the food item through the Range output and can also write the desired feedback.

![image7](https://user-images.githubusercontent.com/54242817/127758014-5f47d9c4-b1c4-4f0c-aaec-01b63fb0b335.png)

Feedback form


**2. Canteen View:**
1) Deposit Money:
The first thing to do is to Deposit Virtual Money in your MES Account. This is the only place  where you are supposed to go for this all Online-Canteen to happen. You have to deposit money  in your account in order to place cashless orders. The amount may be anything which is  comfortable to the students. There is no minimum amount to be deposited.

![image3](https://user-images.githubusercontent.com/54242817/127758009-c5dc1fa0-4933-4906-810f-cb07f6a4586b.png)

Cash Deposit

The transaction is successful and it throws an error when the user is not yet registered.

2) Live Orders:
This is the Section where the main execution of the orders takes place here, the operator in the  canteen will can see two cards:
a) Live Orders:
The orders placed by students and faculty are seen here in the live order section. Here the  operator has the authority to accept and decline the orders according to the availability of  the Items remaining and the will to prepare.
b) Call Out:
Once the orders are accepted by the operator it means that the orders are placed and it is  being cooked. The email notification is sent to the students that his/her orders are placed  and being prepared. When the order is ready to serve then the operator calls the customer  with the Call Out button. Then, the email is sent to inform the students that his/ her orders  are ready to be collected from the counter.

![image5](https://user-images.githubusercontent.com/54242817/127758011-3cbfe5c2-18cc-4b60-9c97-1357811a38bf.png)

Live Orders


**3. Modify menu:**
The items in the menu cannot be continued forever and at some point of time it is necessary to change the price of the food items or to change their images. Any changes that are to be done  with the menu and its associated attributes are done in the Modify Menu section. The Ajax requests are used here to fetch the details of the associated item . 
The User has to only select the name of the item and all the current information stored in the database is displayed in appropriate places. Here the canteen admin can modify the menu. Here we can also add new menu items to be included in the menu.

The image of the food item is required here for easy identification of food in Student View.

![image10](https://user-images.githubusercontent.com/54242817/127758017-ea9a7e24-babd-4212-aecb-dfb30d705156.png)

Modify Canteen Menu.


**4. Metrics:**
The metrics are something which can be very useful to the canteen admin to study the trends and  the favourite items among the students and also can Look into the problem if there exist any.

![image12](https://user-images.githubusercontent.com/54242817/127758019-a3026c59-930e-4a80-83e8-aed851f51543.png)

Metrics - Daily Earnings

It gives an overview of the daily Earning and can be used to study trends in students behaviour.

![image8](https://user-images.githubusercontent.com/54242817/127758015-27bec614-d7a8-439f-bbe6-4138a328fe1c.png)

Metrics - Food Item Sales 

It also shows the sales of food items as it helps in understanding what kind of food is popular  among the students.



**Team Members:**

Aditya Sivaram Nair

Sidharth Shankaranarayanan Nair

Alex Vettithanam

Mohanish Ghate
