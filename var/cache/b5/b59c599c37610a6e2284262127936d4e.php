<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* base_front.html */
class __TwigTemplate_9155ee14399609f7339dab27a12aa780 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"en\">
<head>
  <meta charset=\"UTF-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
  <title>
  ";
        // line 7
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        // line 8
        yield "  - Clothes Fashion Store
  </title>

  <!--
    - favicon
  -->
  <link rel=\"icon\" href=\"";
        // line 14
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "images/logo/favicon.svg\" type=\"image/svg+xml\">

  <!--
    -remix icons
  -->
  <link href=\"";
        // line 19
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "/css/remixicon.css\" rel=\"stylesheet\"/>

  <!--
    -custom css link
  -->
  <link rel=\"stylesheet\" href=\"";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "/css/style.css\">

</head>

<body class=\"home-page\">

  <!--
    - #PRELOADER
  -->

  <div id=\"preloader\"></div>

  <!--
    - #BG LINES
  -->

  <div class=\"lines-wrapper\">
    <div class=\"container\">
      <div class=\"row\">
        <span class=\"line\"></span>
        <span class=\"line\"></span>
        <span class=\"line\"></span>
        <span class=\"line\"></span>
        <span class=\"line\"></span>
        <span class=\"line\"></span>
      </div>
    </div>
  </div>

  <!--
    -page content
  -->

  <div class=\"body-wrapper\">

    <!--
    - #NAV
    -->

    <nav class=\"offcanvas-menu\" data-offcanvas-menu>
      <div class=\"offcanvas-container\">
        <div class=\"offcanvas-logo\">
          <a href=\"/\">
            <img src=\"";
        // line 67
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "images/logo/logo-white.svg\" alt=\"offcanvas logo\">
          </a>
          
        </div>

        <div class=\"offcanvas-navigation\" data-navigation>
        </div>
      </div>

      <div class=\"offvanvas-image\" aria-hidden=\"true\">
        <img src=\"";
        // line 77
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "images/offcanvas-image.webp\" alt=\"offcanvas menu image\">
      </div>

      <button id=\"menu-close\" class=\"close-modal\" data-offcanvas-close aria-label=\"Close the menu\">
      </button>
    </nav>

    <!--
    - #HEADER
    -->

    <header id=\"header\" data-header>
      <div class=\"container-full\">
        <div class=\"row\">
          <div class=\"logo\">
            <a href=\"./\">
              <img src=\"";
        // line 93
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "images/logo/logo.svg\" alt=\"page logo\">
            </a>
          </div>

          <div class=\"header-spacer container\"></div>

          <div class=\"menu-opener\">
            <button id=\"menu-toggler\" data-offcanvas-open aria-label=\"Open the menu\">
              <span aria-hidden=\"true\"></span>
              <span aria-hidden=\"true\"></span>
              <span aria-hidden=\"true\"></span>
            </button>
          </div>
        </div>
      </div>
    </header>

    ";
        // line 110
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 111
        yield "
    <!--
    - #FOOTER
    -->

    <footer id=\"footer\">
      <div class=\"container\">
        <div class=\"row main-grid\">
          <div class=\"footer-logo\">
            <a href=\"/\">
              <img src=\"";
        // line 121
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "images/logo/logo.svg\" alt=\"footer logo\">
            </a>
          </div>

          <div class=\"footer-about\">
            <p>Justo, a quisque in accumsan dignissim volutpat quis. Sit pellentesque faucibus arcu lacinia egestas augue. Sit volutpat vel dui ultricies massa.</p>
          </div>

          <div class=\"footer-contact\">
            <p>
              <a href=\"#\">hello@templatesjungle.com</a><br>
              15Th Street Avenue, New York, USA<br>
              011-554-8798-6556
            </p>
          </div>
        </div>
      </div>
    </footer>


  </div>

  <!--
    -ScrollReveal
  -->
  <script src=\"";
        // line 146
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "/js/scrollreveal.js\"></script>

  <!--
    -custom js
  -->
  <script src=\"";
        // line 151
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "/js/scripts.js\"></script>
</body>
</html>";
        yield from [];
    }

    // line 7
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield " ";
        yield from [];
    }

    // line 110
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "base_front.html";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  245 => 110,  234 => 7,  226 => 151,  218 => 146,  190 => 121,  178 => 111,  176 => 110,  156 => 93,  137 => 77,  124 => 67,  78 => 24,  70 => 19,  62 => 14,  54 => 8,  52 => 7,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en\">
<head>
  <meta charset=\"UTF-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
  <title>
  {% block title %} {% endblock %}
  - Clothes Fashion Store
  </title>

  <!--
    - favicon
  -->
  <link rel=\"icon\" href=\"{{doc_root}}images/logo/favicon.svg\" type=\"image/svg+xml\">

  <!--
    -remix icons
  -->
  <link href=\"{{doc_root}}/css/remixicon.css\" rel=\"stylesheet\"/>

  <!--
    -custom css link
  -->
  <link rel=\"stylesheet\" href=\"{{doc_root}}/css/style.css\">

</head>

<body class=\"home-page\">

  <!--
    - #PRELOADER
  -->

  <div id=\"preloader\"></div>

  <!--
    - #BG LINES
  -->

  <div class=\"lines-wrapper\">
    <div class=\"container\">
      <div class=\"row\">
        <span class=\"line\"></span>
        <span class=\"line\"></span>
        <span class=\"line\"></span>
        <span class=\"line\"></span>
        <span class=\"line\"></span>
        <span class=\"line\"></span>
      </div>
    </div>
  </div>

  <!--
    -page content
  -->

  <div class=\"body-wrapper\">

    <!--
    - #NAV
    -->

    <nav class=\"offcanvas-menu\" data-offcanvas-menu>
      <div class=\"offcanvas-container\">
        <div class=\"offcanvas-logo\">
          <a href=\"/\">
            <img src=\"{{doc_root}}images/logo/logo-white.svg\" alt=\"offcanvas logo\">
          </a>
          
        </div>

        <div class=\"offcanvas-navigation\" data-navigation>
        </div>
      </div>

      <div class=\"offvanvas-image\" aria-hidden=\"true\">
        <img src=\"{{doc_root}}images/offcanvas-image.webp\" alt=\"offcanvas menu image\">
      </div>

      <button id=\"menu-close\" class=\"close-modal\" data-offcanvas-close aria-label=\"Close the menu\">
      </button>
    </nav>

    <!--
    - #HEADER
    -->

    <header id=\"header\" data-header>
      <div class=\"container-full\">
        <div class=\"row\">
          <div class=\"logo\">
            <a href=\"./\">
              <img src=\"{{doc_root}}images/logo/logo.svg\" alt=\"page logo\">
            </a>
          </div>

          <div class=\"header-spacer container\"></div>

          <div class=\"menu-opener\">
            <button id=\"menu-toggler\" data-offcanvas-open aria-label=\"Open the menu\">
              <span aria-hidden=\"true\"></span>
              <span aria-hidden=\"true\"></span>
              <span aria-hidden=\"true\"></span>
            </button>
          </div>
        </div>
      </div>
    </header>

    {% block content %}{% endblock %}

    <!--
    - #FOOTER
    -->

    <footer id=\"footer\">
      <div class=\"container\">
        <div class=\"row main-grid\">
          <div class=\"footer-logo\">
            <a href=\"/\">
              <img src=\"{{doc_root}}images/logo/logo.svg\" alt=\"footer logo\">
            </a>
          </div>

          <div class=\"footer-about\">
            <p>Justo, a quisque in accumsan dignissim volutpat quis. Sit pellentesque faucibus arcu lacinia egestas augue. Sit volutpat vel dui ultricies massa.</p>
          </div>

          <div class=\"footer-contact\">
            <p>
              <a href=\"#\">hello@templatesjungle.com</a><br>
              15Th Street Avenue, New York, USA<br>
              011-554-8798-6556
            </p>
          </div>
        </div>
      </div>
    </footer>


  </div>

  <!--
    -ScrollReveal
  -->
  <script src=\"{{doc_root}}/js/scrollreveal.js\"></script>

  <!--
    -custom js
  -->
  <script src=\"{{doc_root}}/js/scripts.js\"></script>
</body>
</html>", "base_front.html", "C:\\xampp\\htdocs\\clothes-ecommerce\\templates\\base_front.html");
    }
}
