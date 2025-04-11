<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ezily</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body style="background-color: rgb(63,63,63); height: 100vh;">
    <table>
        <tr>
            <td>
                <div class="sideheader">
                    <div class="center">
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'home')" id="defaultOpen">Home</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'create')">Create</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'library')">Querys</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'services')">Services</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="events()">Events</button>
                        <button style="height: 10vh; width: 100%;" class="buttonc tablinks" onclick="opentab(event, 'about')">About</button>
                    </div>
                </div>
            </td>

            <!-- Content / Tabs ------------------------------------------------------------------------------------------------ -->
            <td>
                <div id="create" class="tabcontent">
                    <div class="centerleft">
                        <div class="homesides">
                            <h4 class="centertop" style="font-family: Cambria; color: coral;">Library</h4>
                            <button style="border-top: 2px solid;" class="vtabs"></button>
                            <button class="vtabs" onclick="recentfile()"></button>
                            <button class="vtabs" onclick="recentfile()"></button>
                            <button class="vtabs" onclick="recentfile()"></button>
                            <button class="vtabs" onclick="recentfile()"></button>
                            <button style="border-bottom: 2px solid;" class="vtabs"></button>
                        </div>
                    </div>
                    <div class="centerright">
                        <div class="homesides">
                            <button style="position: absolute; top: 7%; left: 15%;" class="sidebuttons" onclick="googledocs()">
                                <img src="../images/googledocs.jpg" alt="Docs" class="sidebuttonsi">
                            </button>
                            <button style="position: absolute; top: 7%; right: 15%;" class="sidebuttons" onclick=""></button>
                            <button class="centerbottom" style="position: absolute; top:95%; width: 10vw; height: 5vh; width: 100%; border-radius: 50px;" onclick="office()">Microsoft Office</button>
                        </div>
                    </div>
                    <div class="center">
                        <form action="saveto">
                            <textarea class="homecenter" style="background-color: white; color: black;" name="" id=""></textarea>
                        </form>
                    </div>
                </div>

                <div id="home" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <table border="1" style="border-color: white;">
                                <tr>

                                    <td>
                                        <button
                                            style="background-image: linear-gradient(135deg, purple, rgb(255, 205, 255));"
                                            class="servicebubbles"
                                            onclick="spotifyopen()">
                                            <img src="../images/Spotify-logo.png" alt="Spotify">
                                        </button>
                                    </td>

                                    <td>
                                        <button
                                            style="background-image: linear-gradient(225deg, blue, rgb(205, 205, 250));"
                                            class="servicebubbles"
                                            onclick="elearning()">
                                            <img src="../images/logo-main.png" alt="Salcc Logo">
                                        </button>
                                    </td>

                                    <td>
                                        <button
                                            style="background-image: linear-gradient(45deg, orange, rgb(255, 237, 205));"
                                            class="servicebubbles"
                                            onclick="ebscohost()">
                                            <img src="../images/EBSCO.png" alt="EBSCOhost logo">
                                        </button>
                                    </td>

                                </tr>

                                <tr>
                                    <td>
                                        <button
                                            style="background-image: linear-gradient(315deg, red, rgb(255, 205, 205));"
                                            class="servicebubbles"
                                            onclick="youtube()">
                                            <img src="../images/Youtube.png" alt="Spotify">
                                        </button>
                                    </td>

                                    <td></td>

                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

                <div id="library" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <table style="width: 100%;">
                                <tr>
                                    <th colspan="3" style="height: 10%;">
                                        <h2>Select Query type</h2>
                                    </th>
                                </tr>
                                <form action="">
                                    <tr style="height: 90%;">
                                        <td><button class="servicebubbles" onclick="">Financial</button></td>
                                        <td><button class="servicebubbles" onclick="">Sonis / E-learning</button></td>
                                        <td><button class="servicebubbles" onclick="">Subject selction & Electives</button></td>
                                    </tr>
                                </form>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="services" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <table border="1" style="border-color: white;">
                                <tr style="height: 90%">
                                    <td>
                                        <button
                                            class="servicebubbles"
                                            onclick="">
                                            <img src="../images/Le_fort.jpeg" alt="le fort">
                                        </button>
                                    </td>

                                    <td>
                                        <button
                                            class="servicebubbles"
                                            onclick="">
                                            <img src="../images/Student_services.png" alt="support">
                                        </button>
                                    </td>

                                    <td>
                                        <button
                                            class="servicebubbles"
                                            onclick="">
                                            <img src="../images/departments.jpg" alt="">
                                        </button>
                                    </td>
                                </tr>

                                <tr style="height: 10%;">
                                    <td><h3>Le Forte restaurant</h3></td>
                                    <td><h3>Student Affairs</h3></td>
                                    <td><h3>Departments Offices</h3></td>
                                </tr>


                        </div>






                    </div>
                </div>
                </div>

                <div id="about" class="tabcontent">
                    <div class="center">
                        <div class="homecenter">
                            <h4></h4>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

<script src="../source.js"></script>

</html>