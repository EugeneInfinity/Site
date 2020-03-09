<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
              'name' => 'Главная',
              'blade' => 'front.pages.home',
              'url_alias' => '/',
                'safe' => 1,
            ],
            [
                'name' => 'О компании',
                'blade' => 'front.pages.about',
                'url_alias' => 'about',
                'safe' => 1,
            ],
            [
                'name' => 'Политика',
                'blade' => 'front.pages.policy',
                'url_alias' => 'policy',
            ],
            [
                'name' => 'Сотрудничество',
                'blade' => 'front.pages.cooperation',
                'url_alias' => 'cooperation'
            ],
            [
                'name' => 'Оплата и доставка',
                'blade' => 'front.pages.payment',
                'url_alias' => 'payment'
            ],
            [
                'name' => 'Где купить',
                'body' => '<div class="politic__block"><div class="politic__name">Москва</div><p class="small">Навазние магазина, адрес, квартира и прочее</p><p class="small">Навазние магазина, адрес, квартира и прочее</p><p class="small">Навазние магазина, адрес, квартира и прочее</p><div class="politic__name">Санкт-Петербург</div><p class="small">Навазние магазина, адрес, квартира и прочее</p><p class="small">Навазние магазина, адрес, квартира и прочее</p><p class="small">Навазние магазина, адрес, квартира и прочее</p></div><div class="politic__block"><div class="politic__name">Москва</div><p class="small">Навазние магазина, адрес, квартира и прочее</p><p class="small">Навазние магазина, адрес, квартира и прочее</p><p class="small">Навазние магазина, адрес, квартира и прочее</p><div class="politic__name">Санкт-Петербург</div><p class="small">Навазние магазина, адрес, квартира и прочее</p><p class="small">Навазние магазина, адрес, квартира и прочее</p><p class="small">Навазние магазина, адрес, квартира и прочее</p></div>',
                'blade' => null,
                'url_alias' => 'buy'
            ],
            [
                'name' => 'Вопросы и контакты',
                'blade' => 'front.pages.faq',
                'url_alias' => 'faq',
                'body' => '<p class="wrapper-inner__text">Пожалуйста, делитесь любыми вопросами и пожеланиями. Мы ответим настолько быстро, насколько это возможно. Вы получите ответ на указанный адрес электронной почты.<br><br><span class="down-content">Горячая линия MATRIX доступна по телефонам: 8 800 500 21 32 , +7 499 677 10 34 (Для Казахстана)</span></p>'
            ],
            [
                'name' => 'Вопрос-ответ',
                'blade' => 'front.pages.questions',
                'url_alias' => 'questions'
            ],
            [
                'name' => 'Контакты',
                'body' => '<p class="communication__text">Отправляйте ваши вопросы или пожелания. Мы обязательно ответим.<br/>Вы получите ответ на указанный адрес электронной почты.</p>',
                'blade' => 'front.pages.contacts',
                'url_alias' => 'contacts',
                'safe' => 1,
            ],
            [
                'name' => 'ОБУЧЕНИЕ ПАРИКМАХЕРОВ И СТИЛИСТОВ',
                'body' => '<div class="g-block"> <div class="g-block__left"> <img src="/its-client/img/girl-vebinar.png" alt=""> </div> <div class="g-block__right"> <h2 class="g-block__right-title"> HIPERTIN SCHOOL </h2> <p class="g-block__right-text"> <span> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget ex vel mi varius consectetur eu sit amet augue . Duis tempus pharetra justo, et gravida lacus ultricies a. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. <br> </span> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget ex vel mi varius consectetur eu sit amet augue. Duis tempus pharetra justo, et gravida lacus ultricies a. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque vehicula elit nec neque mattis pharetra. Sed molestie ipsum vitae nulla dignissim euismod. Aenean vel ligula fringilla, pretium ipsum et, ultrices sapien. </p> <a href="#" class="btn-gen more">Подробнее </a> </div> </div> <div class="g-block"> <div class="g-block__left"> <img src="/its-client/img/girl-vebinar.png" alt=""> </div> <div class="g-block__right"> <h2 class="g-block__right-title"> HIPERTIN SCHOOL </h2> <p class="g-block__right-text"> <span> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget ex vel mi varius consectetur eu sit amet augue . Duis tempus pharetra justo, et gravida lacus ultricies a. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. <br> <br> </span> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eget ex vel mi varius consectetur eu sit amet augue. Duis tempus pharetra justo, et gravida lacus ultricies a. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque vehicula elit nec neque mattis pharetra. Sed molestie ipsum vitae nulla dignissim euismod. Aenean vel ligula fringilla, pretium ipsum et, ultrices sapien. Sed scelerisque, purus non dignissim pharetra, nisi mauris posuere quam, non sagittis tortor odio id neque. </p> </div> </div>',
                'blade' => 'front.pages.study',
                'url_alias' => 'study',
                'safe' => 0,
            ],
            [
                'name' => 'Компания',
                'body' => '<div class="company__content company__content_first"><div class="company__block"><img src="/its-client/img/about-company-1.png" alt=""><div class="company__info"><h1 class="company__name">Верность традициям</h1><p class="company__text">Бренд Hipertin зародился как небольшое семейное предприятие в 1944 году недалеко от Барселоны.</p><p class="company__text">За 75 лет развития компания заслужила репутацию надежного европейского производителя профессиональной косметики для волос.</p></div></div></div><div class="company__content company__content_bg"><div class="company__block company__block_line"><div class="company__info"><h2 class="company__name">Высокие стандарты</h2><p class="company__text">Hipertin с первого дня основания разрабатывала косметику для профессионалов и их клиентов, поэтому к продукции изначально предъявлялись строгие требования. Технологи бренда располагают современной лабораторией и оборудованием, и контролируют каждый этап производства. Продукция отвечает не только косметическим, но и испанским медицинским стандартам, которые считаются одними из самых высоких в Европе.</p></div></div></div><div class="company__content company__content_three"><div class="company__block"><img src="/its-client/img/about-company-2.png" alt=""><div class="company__info"><h2 class="company__name">Превосходное соотношение цены и качества</h2><p class="company__text">Компания внимательно следит за пожеланиями клиентов. Полный контроль над собственным производством позволяет Hipertin поддерживать оправданные цены.</p></div></div></div><div class="company__content  company__content_inner company__content_bg"><h2 class="company__name">Без консервантов и искусственных ароматизаторов</h2><div class="company__block"><img class="dekstop" src="/its-client/img/about-company-3.png" alt=""><img class="mobile" src="/its-client/img/company-mobile.png" alt=""><div class="company__info"><p class="company__text">Средства для ухода за волосами от Hipertin создаются с использованием натуральных ингредиентов, прошедших несколько стадий очистки. Это повышает содержание в их составе активных веществ, которые увеличивают эффективность продуктов. Средства для стайлинга и серии для окрашивания содержат только протестированные гипоаллергенные компоненты, поэтому они деликатно воздействуют на волос и не травмируют его.</p></div></div></div><div class="company__content"><div class="company__block company__block_reverse"><div class="company__info"><h2 class="company__name">Широкий выбор продукции</h2><p class="company__text">Ассортимент Hipertin способен полностью обеспечить работу салона. Бренд предлагает большой выбор продуктов для осветления, окрашивания, химической завивки, ухода за волосами и стайлинга. И мастера, и их клиенты найдут необходимые им средства.</p></div><img src="/its-client/img/about-company-4.png" alt=""></div></div><div class="company__content company__content_last company__content_bg"><div class="company__block"><img src="/its-client/img/about-company-5.png" alt=""><div class="company__info"><h2 class="company__name">Забота об <br> окружающей среде</h2><p class="company__text">Косметика Hipertin не тестируется на животных. Производство бренда сертифицировано в соответствии с европейскими стандартами качества экологической безопасности.</p></div></div></div>',
                'blade' => 'front.pages.company',
                'url_alias' => 'company'
            ],
        ];

        foreach ($pages as $item) {
            $page = \App\Models\Page::create([
                'name' => $item['name'],
                'body' => $item['body'] ?? null,
                'blade' => $item['blade'],
                'safe' => $item['safe'] ?? 0,
            ]);
            $page->urlAlias()->updateOrCreate([],[
                'alias' => $item['url_alias'],
                'source' => $page->generateUrlSource()
            ]);
        }

        $this->command->info('Pages seed success!');
    }
}
