<!DOCTYPE html>
<html lang="en">
    <head>
        <title>My notes - Signup</title>
        <?php include('head.html'); ?>
        <base href="<?= $web_root ?>"/>
    </head>
    <body data-bs-theme="dark" class="h-full">
        <div class="container h-100">
            <div class="row align-items-center h-full">
                <div class="col-12">
                    <div class="card h-100 justify-content-center" style="border-radius: 1rem;">
                    <div class="card-body p-3 text-center">
                    <h3 class="mb-2">Sign up</h3>

                    <hr class="my-4">

                    <form id="signupForm" action="main/signup" method="post">

                        <div class="input-group flex-nowrap mt-2 w-100">
                            <button class="btn btn-outline-secondary text-white" type="button" disabled><i class="bi bi-person"></i></button>
                            <input id="mail" name="mail" type="email" class="form-control" value="<?= $mail ?>" placeholder="exemple@domaine.ect" >
                        </div>

                        <div class="input-group flex-nowrap mt-2 w-100">
                            <button class="btn btn-outline-secondary text-white" type="button" disabled><i class="bi bi-person"></i></button>
                            <input id="fullname" name="fullname" type="text" class="form-control" value="<?= $fullname ?>" placeholder="Full name" >
                        </div>

                        <div class="input-group flex-nowrap mt-2 w-100">
                            <button class="btn btn-outline-secondary text-white" type="button" disabled><i class="bi bi-key"></i></button>
                            <input id="password" name="password" type="password" class="form-control" value="<?= $password ?>" placeholder="Password" >
                        </div>

                        <div class="input-group flex-nowrap mt-2 w-100">
                            <button class="btn btn-outline-secondary text-white" type="button" disabled><i class="bi bi-key"></i></button>
                            <input id="password_confirm" name="password_confirm" type="password" class="form-control" value="<?= $password_confirm ?>" placeholder="Confirm your password" >
                        </div>

                        <button class="btn btn-primary btn-lg btn-block mt-3 w-100" type="submit">Sign Up</button>

                        <a class="btn btn-danger btn-lg btn-block mt-2 w-100" href="main/index" role="button" type="submit">Cancel</a>

                    </form>

                    <?php if (count($errors) != 0): ?>
                        <div class='errors'>
                            <br><br><p>Please correct the following error(s) :</p>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.html'); ?>
    </body>
</html> 