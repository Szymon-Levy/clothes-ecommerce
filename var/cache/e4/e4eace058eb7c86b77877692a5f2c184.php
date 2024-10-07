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

/* index.html */
class __TwigTemplate_7453bb619e36745258f4f0a275303849 extends Template
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

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base_front.html";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("base_front.html", "index.html", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Home";
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "  <!--
  - #HOME
  -->

  <section class=\"home-section\">
    <div class=\"container\">
      <div class=\"row main-grid\">
        <div class=\"content-column\">
          <div class=\"headline\">collection 2020</div>
          <h3 class=\"section-title title-500\" data-heading-slide-from-left>New Arrivals</h4>
          <p data-text-content-slide-from-bottom>Mattis nisl eu quam elementum vulputate ultrices blandit. Congue molestie et varius risus, tristique fermentum purus nunc sed. Et tristique cursus felis placerat proin aliquam. Faucibus aliquam nam morbi volutpat scelerisque cursus in est. Posuere lacus risus faucibus a morbi aliquam malesuada sed sed.</p>
          <a href=\"#\" class=\"text-btn\">Shop now</a>
        </div>

        <div class=\"video-popup-column\">
          <div class=\"video-popup-image-wrapper\" data-video-popup-image data-popup-video-src=\"";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "/videos/popup-video.mp4\">
            <img class=\"video-popup-image\" src=\"";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "/images/home-section/video-popup-image.webp\" alt=\"video popup image\">
            <button class=\"video-popup-icon\" data-video-popup-open>
              <img src=\"";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "/images/icons/video-popup-icon.svg\" alt=\"video popup\">
            </button>
          </div>
        </div>

        <div class=\"image-column\">
          <img src=\"";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["doc_root"] ?? null), "html", null, true);
        yield "/images/home-section/home-image.webp\" alt=\"two models in red outfit\" data-image-slide-from-right>
        </div>
      </div>
    </div>
  </section>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "index.html";
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
        return array (  105 => 30,  96 => 24,  91 => 22,  87 => 21,  70 => 6,  63 => 5,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base_front.html' %}

{% block title %}Home{% endblock %}

{% block content %}
  <!--
  - #HOME
  -->

  <section class=\"home-section\">
    <div class=\"container\">
      <div class=\"row main-grid\">
        <div class=\"content-column\">
          <div class=\"headline\">collection 2020</div>
          <h3 class=\"section-title title-500\" data-heading-slide-from-left>New Arrivals</h4>
          <p data-text-content-slide-from-bottom>Mattis nisl eu quam elementum vulputate ultrices blandit. Congue molestie et varius risus, tristique fermentum purus nunc sed. Et tristique cursus felis placerat proin aliquam. Faucibus aliquam nam morbi volutpat scelerisque cursus in est. Posuere lacus risus faucibus a morbi aliquam malesuada sed sed.</p>
          <a href=\"#\" class=\"text-btn\">Shop now</a>
        </div>

        <div class=\"video-popup-column\">
          <div class=\"video-popup-image-wrapper\" data-video-popup-image data-popup-video-src=\"{{doc_root}}/videos/popup-video.mp4\">
            <img class=\"video-popup-image\" src=\"{{doc_root}}/images/home-section/video-popup-image.webp\" alt=\"video popup image\">
            <button class=\"video-popup-icon\" data-video-popup-open>
              <img src=\"{{doc_root}}/images/icons/video-popup-icon.svg\" alt=\"video popup\">
            </button>
          </div>
        </div>

        <div class=\"image-column\">
          <img src=\"{{doc_root}}/images/home-section/home-image.webp\" alt=\"two models in red outfit\" data-image-slide-from-right>
        </div>
      </div>
    </div>
  </section>
{% endblock %}", "index.html", "C:\\xampp\\htdocs\\clothes-ecommerce\\templates\\index.html");
    }
}
