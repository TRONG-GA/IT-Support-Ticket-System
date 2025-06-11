<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}
//thêm vào danh sách yêu thích
if (isset($_POST['add_to_wishlist'])) {

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];

   $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_wishlist_numbers) > 0) {
      $message[] = 'Sản phẩm này đã được thêm vào danh sách yêu thích từ trước';
   } elseif (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'Sản phẩm này đã được thêm vào giỏ hàng từ trước';
   } else {
      mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
      $message[] = 'Đã thêm sản phẩm vào danh yêu thích';
   }
}

if (isset($_POST['add_to_cart'])) {

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'Sản phẩm này đã được thêm vào giỏ hàng từ trước đo';
   } else {

      $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_wishlist_numbers) > 0) {
         mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
      }

      mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'Đã thêm sản phẩm vào giỏ hàng';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>

   <?php @include 'header.php'; ?>

   
   <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="2000">
      <img src="https://theme.hstatic.net/1000388227/1001117190/14/slider_1_image.jpg?v=994" class="d-block hoa w-100"  alt="..."  style="height: 600px; object-fit:cover;">
    </div>
    <div class="carousel-item" data-bs-interval="2000">
      <img src="https://i.ytimg.com/vi/UElPBnfqs7o/maxresdefault.jpg" class="d-block hoa w-100"  alt="..." style="height: 600px; object-fit:cover;">
    </div>
    <div class="carousel-item">
      <img src="https://theme.hstatic.net/1000388227/1001117190/14/slider_2_image.jpg?v=994" class="d-block hoa w-100"  alt="..." style="height: 600px; object-fit:cover;">
    </div>
    <div class="carousel-item">
      <img src="https://theme.hstatic.net/1000388227/1001117190/14/slider_4_image.jpg?v=994" class="d-block hoa w-100"  alt="..." style="height: 600px; object-fit:cover;">
    </div>
    <div class="carousel-item">
      <img src="https://theme.hstatic.net/1000388227/1001117190/14/slider_3_image.jpg?v=994" class="d-block hoa w-100"  alt="..." style="height: 600px; object-fit:cover;">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

   

   <section class="products">

      <h1 class="title">Danh sách các sảm phẩm</h1>

      <div class="box-container">

         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
         ?>
               <form action="" method="POST" class="box">
                  <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
                  <div class="price">$<?php echo $fetch_products['price']; ?></div>
                  <img src="./flowers/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <input type="number" name="product_quantity" value="1" min="0" class="qty">
                  <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                  <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                  <!-- <img src="./flowers/<?php echo $fetch_products['image']; ?>" name="product_image" value="" style="width: 280px;px;height:400px;"> -->
                  <input type="submit" value="Thêm yêu thích" name="add_to_wishlist" class="option-btn">
                  <input type="submit" value="Thêm giỏ hàng" name="add_to_cart" class="btn" style="background: #0d6efd;padding: 1rem 3rem;color:white;font-size: 16px;">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>

      </div>

      <div class="more-btn">
         <a href="shop.php" class="option-btn">load more</a>
      </div>

   </section>

   <section class="home-contact">

      <div class="content">
         <h3>Đồ Án - Nhóm 1</h3>
         <p>Hãy liên hệ với chúng tôi</p>
         <a href="contact.php" class="btn">Click</a>
      </div>

   </section>
   <?php @include 'footer.php'; ?>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
   <script src="js/script.js"></script>

</body>

</html>