    
    

    <?php
    $host="localhost";$user="root";$password="";$database="db_milktea";
    $conn=mysqli_connect($host,$user,$password,$database);
    if(!$conn){die("Connection Failed: ".mysqli_connect_error());}
    
    //Query 8
    $totalSales=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(Products.price*Order_Details.quantity) total_sales FROM Order_Details JOIN Products ON Order_Details.product_id=Products.product_id"));
    $customerCount=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM Customers"));
    $productCount=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM Products"));
    $orderCount=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM Orders"));





if (isset($_POST['add_customer'])) {
    $name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $sql = "INSERT INTO Customers (customer_name) VALUES ('$name')";
    mysqli_query($conn, $sql);
}







// CREATE ORDER BTN!!!!!!
if (isset($_POST['create_order'])) {
    $customer_id = intval($_POST['customer_id']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    
    $order_sql = "INSERT INTO Orders (customer_id, order_date) VALUES ($customer_id, NOW())";
    if (mysqli_query($conn, $order_sql)) {
        $order_id = mysqli_insert_id($conn); 

        $detail_sql = "INSERT INTO Order_Details (order_id, product_id, quantity) VALUES ($order_id, $product_id, $quantity)";
        mysqli_query($conn, $detail_sql);
    }
}



      if (isset($_POST['delete_order_id'])) {
          $order_id = intval($_POST['delete_order_id']);

          
          $delete_details = "DELETE FROM Order_Details WHERE order_id = $order_id";
          mysqli_query($conn, $delete_details);

          
          $delete_order = "DELETE FROM Orders WHERE order_id = $order_id";
          if (mysqli_query($conn, $delete_order)) {
              echo "<script>alert('Order deleted successfully!'); window.location.href=window.location.href;</script>";
          } else {
              echo "<script>alert('Error deleting order: " . mysqli_error($conn) . "');</script>";
          }
}

?>








        <!DOCTYPE html>
        <html>
        <head>
        <title>Milk Tea Shop Management System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        body{background:#f5f5f5;}
        .header {
          background: #000;       
          color: #fff;            
          padding: 20px;
          border-radius: 12px;
          margin-bottom: 20px;        
        }

        .card h3,
        .card h5{
            color:#000;
        }
        .card-box{border-radius:12px;box-shadow:0 3px 10px rgba(0,0,0,.1);}
        table{background:#fff;}
        </style>
        </head>
        <body>
        <div class="container mt-4">
        <div class="header text-center">
        <h1>Milk Tea NG TATLONG CODERSSSS</h1>
        </div>


        <div class="text-center mb-4">


       
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addCustomerModal"> Add Customer </button>

            <div class="modal fade" id="addCustomerModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                    <h5 class="modal-title">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <input type="text" name="customer_name" class="form-control mb-3" placeholder="Customer Name" required>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" name="add_customer" class="btn btn-dark">Save</button>
                    </div>
                </form>
                </div>
            </div>
            </div>

            


      
      <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createOrderModal"> Create Order </button>

      <div class="modal fade" id="createOrderModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="POST" action="">
              <div class="modal-header">
                <h5 class="modal-title">Create Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
         
              



          <label>Customer</label>
          <select name="customer_id" class="form-control mb-3" required>
            <?php
            $customers = mysqli_query($conn, "SELECT * FROM Customers");
            while ($c = mysqli_fetch_assoc($customers)) {
              echo "<option value='{$c['customer_id']}'>{$c['customer_name']}</option>";
            }
            ?>
          </select>


          
          
          

          <label>Product</label>
          <select name="product_id" class="form-control mb-3" required>
            <?php
            $products = mysqli_query($conn, "SELECT * FROM Products");
            while ($p = mysqli_fetch_assoc($products)) {
              echo "<option value='{$p['product_id']}'>{$p['product_name']} (₱{$p['price']})</option>";
            }
            ?>
          </select>

          
          
          <label>Quantity</label>
          <input type="number" name="quantity" class="form-control mb-3" min="1" required>
        </div>
        <div class="modal-footer">
          <button type="submit" name="create_order" class="btn btn-dark">Save Order</button>
          </div>
          </form>
          </div>
          </div>
          </div>



</div>




        <div class="row mb-4">
            <div class="col-md-3">
              <div class="card card-box p-3 text-center">
                <h5>Customers</h5><h2><?php echo $customerCount['total']; ?>
              </h2>
            </div>
          </div>
          
            <div class="col-md-3"><div class="card card-box p-3 text-center">
              <h5>Products</h5><h2><?php echo $productCount['total']; ?>
            </h2>
          </div>
        </div>


            <div class="col-md-3"><div class="card card-box p-3 text-center">
              <h5>Orders</h5><h2><?php echo $orderCount['total']; ?>
            </h2>
          </div>
        </div>



        <!-- total sales -->
            <div class="col-md-3"><div class="card card-box p-3 text-center">
              <h5>Total Sales</h5><h2>₱<?php echo $totalSales['total_sales']; ?>
            </h2>
            </div>
            </div>
            </div>


            //Query 1
            <div class="card card-box p-3 mb-4">
            <h3>Customers</h3>
            <table class="table table-bordered table-hover">
            <thead class="table-dark">
              <tr><th>ID</th>
              <th>Name</th>
              </tr>
            </thead>
            <tbody>
            <?php $customers=mysqli_query($conn,"SELECT * FROM Customers");
            while($row=mysqli_fetch_assoc($customers)){ ?>
            <tr><td><?php echo $row['customer_id'];?></td><td><?php echo $row['customer_name'];?></td>
            <?php } ?>
            </tbody>
            </table>
            </div>




      //Query 2
        <div class="card card-box p-3 mb-4">
        <h3> products name and price</h3>
        <table class="table table-bordered table-hover">
        <thead class="table-dark">
          <tr><th>ID</th>
          <th>Product</th>
          <th>Price</th>
          </tr>
          </thead>
          <tbody>
        <?php $products=mysqli_query($conn,"SELECT * FROM Products");
        while($row=mysqli_fetch_assoc($products)){ ?>
        <tr><td><?php echo $row['product_id'];?></td><td><?php echo $row['product_name'];?></td>
        <td>
          ₱<?php echo $row['price'];?></td>
        <?php } ?>
        </tbody>
        </table>
        </div>

        
        
        //Query 3
      <div class="card card-box p-3 mb-4">
      <h3>Orders with Customer Names</h3>
      <table class="table table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Order Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $orders = mysqli_query($conn,
           "SELECT o.order_id, 
                   c.customer_name, 
                   o.order_date 
          FROM Orders o 
          JOIN Customers c 
          ON o.customer_id = c.customer_id");
          while ($row = mysqli_fetch_assoc($orders)) { ?>         

            <tr>
              <td><?php echo $row['order_id']; ?></td>
              <td><?php echo $row['customer_name']; ?></td>
              <td><?php echo $row['order_date']; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>




        //Query 6
        <div class="card card-box p-3 mb-4">
        <h3>Orders</h3>
        <table class="table table-bordered table-hover">
        <thead class="table-dark"><tr>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $q="SELECT Orders.order_id,
                    Customers.customer_name,
                    Products.product_name,
                    Products.price,
                    Order_Details.quantity,
                    Orders.order_date 
            FROM Order_Details 
            JOIN Orders ON Order_Details.order_id=Orders.order_id 
            JOIN Customers ON Orders.customer_id=Customers.customer_id 
            JOIN Products ON Order_Details.product_id=Products.product_id 
            ORDER BY Orders.order_id ASC";
        $orders=mysqli_query($conn,$q);
        while($row=mysqli_fetch_assoc($orders)){ $t=$row['price']*$row['quantity']; ?>
        <tr>
        <td><?php echo $row['order_id'];?></td>
        <td><?php echo $row['customer_name'];?></td>
        <td><?php echo $row['product_name'];?></td>
        <td><?php echo $row['quantity'];?></td>
        <td>₱<?php echo $t;?></td>
        <td><?php echo $row['order_date'];?></td>
        <td>
          <form method="POST" action="" style="display:inline;">
            <input type="hidden" name="delete_order_id" value="<?php echo $row['order_id']; ?>">
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Void this order?');">Void</button>
          </form>
        </td>

        </tr>
        <?php } ?>
        </tbody></table>
        </div>



        <div class="container mt-4">
        <div class="header text-center">
        <h1>Sales Report</h1>
        </div>
        


  
//Query 7
<div class="card card-box p-3 mb-4" id="topProducts"> 
  <h3>Total sold per products</h3> 
  <table class="table table-bordered table-hover"> 
    <thead class="table-dark"> 
      <tr> 
        <th>Product Name</th> 
        <th>Total Sold</th> 
      </tr> 
    </thead> 
    <tbody> 
      <?php
      
      $q = "SELECT p.product_name, SUM(od.quantity) AS TotalSold
            FROM Order_Details od
            JOIN Products p ON od.product_id = p.product_id
            GROUP BY p.product_name
            ORDER BY TotalSold DESC";

      $result = mysqli_query($conn, $q);

      if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$row['product_name']}</td>
                      <td>{$row['TotalSold']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='2'>Error: " . mysqli_error($conn) . "</td></tr>";
      }
      ?> 
    </tbody> 
    </table> 
    </div>



  





          //Query 4
            <div class="card card-box p-3 mb-4" id="customerOrders">

            <h3>Total Orders Per Customer</h3>

            <table class="table table-bordered table-hover">

            <thead class="table-dark">
            <tr>
            <th>Customer Name</th>
            <th>Total Orders</th>
            </tr>
            </thead>

            <tbody>

            <?php

            $q = "
            SELECT c.customer_name,
            COUNT(o.order_id) AS TotalOrders
            FROM Customers c
            JOIN Orders o
            ON c.customer_id = o.customer_id
            GROUP BY c.customer_name
            ORDER BY TotalOrders DESC
            ";

            $result = mysqli_query($conn, $q);

            while($row = mysqli_fetch_assoc($result)){

            ?>

            <tr>
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['TotalOrders']; ?></td>
            </tr>

            <?php } ?>

            </tbody>
            </table>

            </div>





            //Query 5
              <div class="card card-box p-3 mb-4" id="customerPurchases">

              <h3>total purchase amount of each customer</h3>

              <table class="table table-bordered table-hover">

              <thead class="table-dark">
              <tr>
              <th>Customer Name</th>
              <th>Total Purchase</th>
              </tr>
              </thead>

              <tbody>

              <?php

              $q = " SELECT c.customer_name,SUM(p.price * od.quantity) AS TotalPurchase
              FROM Customers c
              JOIN Orders o
              ON c.customer_id = o.customer_id
              JOIN Order_Details od
              ON o.order_id = od.order_id
              JOIN Products p
              ON od.product_id = p.product_id
              GROUP BY c.customer_name
              ORDER BY TotalPurchase DESC";

              $result = mysqli_query($conn, $q);
              while($row = mysqli_fetch_assoc($result)){
              ?>
              <tr>
              <td><?php echo $row['customer_name']; ?></td>
              <td>₱<?php echo number_format($row['TotalPurchase'],2); ?></td>
              </tr>
              <?php 
              } ?>

              </tbody>
              </table>

              </div>

              



        


</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>