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

/* core/themes/olivero/templates/datetime-wrapper.html.twig */
class __TwigTemplate_d53e34dfc07240fc0ba09c9f2ed384c5 extends Template
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
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 10
        $context["title_classes"] = ["form-item__label", ((        // line 12
($context["required"] ?? null)) ? ("js-form-required") : ("")), ((        // line 13
($context["required"] ?? null)) ? ("form-required") : (""))];
        // line 16
        yield "<div class=\"form-item form-datetime-wrapper\">
  ";
        // line 17
        if (($context["title"] ?? null)) {
            // line 18
            yield "    <h4";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [($context["title_classes"] ?? null)], "method", false, false, true, 18), 18, $this->source), "html", null, true);
            yield ">";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 18, $this->source), "html", null, true);
            yield "</h4>
  ";
        }
        // line 20
        yield "  ";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 20, $this->source), "html", null, true);
        yield "
  ";
        // line 21
        if (($context["errors"] ?? null)) {
            // line 22
            yield "    <div class=\"form-item__error-message\">
      ";
            // line 23
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null), 23, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 26
        yield "  ";
        if (($context["description"] ?? null)) {
            // line 27
            yield "    <div";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["description_attributes"] ?? null), "addClass", ["form-item__description"], "method", false, false, true, 27), 27, $this->source), "html", null, true);
            yield ">
      ";
            // line 28
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["description"] ?? null), 28, $this->source), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 31
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["required", "title", "title_attributes", "content", "errors", "description", "description_attributes"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "core/themes/olivero/templates/datetime-wrapper.html.twig";
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
        return array (  91 => 31,  85 => 28,  80 => 27,  77 => 26,  71 => 23,  68 => 22,  66 => 21,  61 => 20,  53 => 18,  51 => 17,  48 => 16,  46 => 13,  45 => 12,  44 => 10,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "core/themes/olivero/templates/datetime-wrapper.html.twig", "/var/www/html/core/themes/olivero/templates/datetime-wrapper.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 10, "if" => 17);
        static $filters = array("escape" => 18);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape'],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
