//  receive get + post data and dispatch them beetween controllers

[GET] url = /login ->  redirige sur login_controller ->  action index -> render login vue
[POST] url /login ( username=admin, password=clemen51) -> redirige sur login_controller -> action login (utilise models pour interagir avec la bdd) -> if login ok -> welcome.php
                                                                                                        -> if login ko -> login.php with errors