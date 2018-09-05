<?php

class CsAmWelcomeContent {

    function printBanner() {
        ?>
        <article class="page<?php
        if (get_theme_mod('page_breadcrumbs')) {
            print(' has-breadcrumbs');
        }
        ?>">
            <figure class="container">
                <img src="https://static.animagia.pl/Amagi1.jpg" alt="Amagi"/>
                <div class="image-text">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris semper dapibus eros.
                        Quisque et rutrum purus, semper rhoncus purus. Donec libero odio, eleifend a purus
                        lobortis, accumsan cursus urna. Pellentesque et pharetra metus. Mauris lobortis
                        metus eu tortor blandit, in viverra est ultricies.
                    </p>
                </div>
            </figure>
        </article>
        <aside class="aside">
            <ul class="icon-container">
                <li class="mmenuitem">
                    <img class="image" src="https://dev.animagia.pl/movie_creation.png" alt="movie"/>
                    <p>Lorem ipsum dolor sit amet</p>
                </li>
                <li class="mmenuitem">
                    <img class="image" src="https://dev.animagia.pl/lock.png" alt="lock"/>
                    <p>Lorem ipsum dolor sit amet</p>
                </li>
                <li class="mmenuitem">
                    <img class="image" src="https://dev.animagia.pl/ondemand_video.png" alt="video"/>
                    <p>Lorem ipsum dolor sit amet</p>
                </li>
            </ul>
        </aside>
        <?php
    }
}