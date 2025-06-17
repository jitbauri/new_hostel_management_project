<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"/>
   <link rel="stylesheet" href="css\aboutme.css">
   <title>Contact us</title>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
*
{
   margin: 0;
   padding: 0;
   box-sizing: border-box;
  font-family: "Encode Sans Expanded",sans-serif;
}
body
{
   /* display: flex; */
   justify-content: center;
   align-items: center;
   min-height: 100vh;
   width: 100%;
   background: #f2f3f7;
}
.heading{
margin-left: 45%;
margin-top: 45px;
margin-bottom: 40px;

}
.container
{
   display: flex;
   justify-content: center;
   align-items: center;
   flex-wrap: wrap;
}
.container .card
{
   width: 330px;
   height: 416px;
   padding: 60px 30px;
   margin: 20px;
   background: #f2f3f7;
   box-shadow: 0.6em 0.6em 1.2em #d2dce9,
               -0.5em -0.5em 1em #ffffff;
   border-radius: 20px;
}
.container .card .content
{
   display: flex;
   justify-content: center;
   align-items: center;
   flex-direction: column;
}
.container .card .content .imgBx
{
   width: 180px;
   height: 180px;
   border-radius: 50%;
   position: relative;
   margin-bottom: 20px;
   overflow: hidden;
}
.container .card .content .imgBx img
{
   position: absolute;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   object-fit: cover;
}
.container .card .content .contentBx h4
{
   color: #36187d;
   font-size: 1.7rem;
   font-weight: bold;
   text-align: center;
   letter-spacing: 1px;
}
.container .card .content .contentBx h5
{
   color: #6c758f;
   font-size: 1.2rem;
   font-weight: bold;
   text-align: center;
}
.container .card .content .sci
{
   margin-top: 20px;
}
.container .card .content .sci a
{
   text-decoration: none;
   color: #6c758f;
   font-size: 30px;
   margin: 10px;
   transition: color 0.4s;
}
.container .card .content .sci a:hover
{
   color: #0196e3;
}
</style>
</head>
<body>
  <div class="heading" >
      <h1>Our Team</h1>
  </div>

    <div class="container">

        <div class="card">
            <div class="content">
                <div class="imgBx">
                    <img src="images/jit_profile.gif" alt="">
                </div>
                <div class="contentBx">
                    <h4>Jit Bauri</h4>
                    <h5>Student</h5>
                </div>
                <div class="sci">
                    <a href="https://www.facebook.com/profile.php?id=100081028891131"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a href="https://www.linkedin.com/in/jit-bauri-440332284/"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    <a href="https://x.com/_jitbauri"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="content">
                <div class="imgBx">
                    <img src="images/jaydeb_img.jpg" alt="">
                </div>
                <div class="contentBx">
                    <h4>jaydeb Das</h4>
                    <h5>Student</h5>
                </div>
                <div class="sci">
                    <a href="https://www.facebook.com/jaydeb.das.440668"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    <a href="https://www.linkedin.com/in/jaydeb-das-4b18b5294/"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    <a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.instagram.com%2Freel%2FCj8HrDgp%3Ffbclid%3DIwZXh0bgNhZW0CMTAAYnJpZBExbmlLMU5mcUhKaDBpa2hyNAEer4x1AJe3V0OKLJIRsDyE9Jb_aQZ7KY-z-AAaj33NccroIw2l2PkpR35oMY4_aem_J82WnDscDQHWTaDoLTdJng&h=AT3MieKYidAHdjje5rjrilMqL7yu716Gu9Z-LO0U-y-RUVMatycICf4QnX2ByXLp76QpKmKH9AVaamWnmZWR9yfZTGDFBlUz9IJCd7W-Z8gjxJmGT0HOkQoJUKorlBj3mZ9t"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>

    </div>
</body>
</html>