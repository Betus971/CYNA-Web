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

/* @ApiPlatform/DataCollector/request.html.twig */
class __TwigTemplate_23042310b4bc9fb397fed30456dc229c extends Template
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
            'toolbar' => [$this, 'block_toolbar'],
            'menu' => [$this, 'block_menu'],
            'panel' => [$this, 'block_panel'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "@WebProfiler/Profiler/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@ApiPlatform/DataCollector/request.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "@ApiPlatform/DataCollector/request.html.twig"));

        // line 85
        $macros["apiPlatform"] = $this->macros["apiPlatform"] = $this;
        // line 1
        $this->parent = $this->load("@WebProfiler/Profiler/layout.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 87
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_toolbar(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "toolbar"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "toolbar"));

        // line 88
        yield "    ";
        $context["icon"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 89
            yield "        ";
            $context["status_color"] = (((($tmp = ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["collector"] ?? null), "counters", [], "any", false, true, false, 89), "ignored_filters", [], "any", true, true, false, 89)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 89, $this->source); })()), "counters", [], "any", false, false, false, 89), "ignored_filters", [], "any", false, false, false, 89), false)) : (false))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("yellow") : ("default"));
            // line 90
            yield "        ";
            yield Twig\Extension\CoreExtension::include($this->env, $context, "@ApiPlatform/DataCollector/api-platform-icon.svg");
            yield "
    ";
            yield from [];
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 92
        yield "
    ";
        // line 93
        $context["text"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 94
            yield "        ";
            if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 94, $this->source); })()), "resources", [], "any", false, false, false, 94)) == 0)) {
                // line 95
                yield "            <div class=\"sf-toolbar-info-piece\">
                <b>Resource Class</b>
                <span>Not an API Platform resource</span>
            </div>
        ";
            }
            // line 100
            yield "        ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 100, $this->source); })()), "resources", [], "any", false, false, false, 100));
            foreach ($context['_seq'] as $context["_key"] => $context["resource"]) {
                // line 101
                yield "            <div class=\"sf-toolbar-info-piece\">
                <b>Resource Class</b>
                <span>";
                // line 103
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["resource"], "resourceClass", [], "any", false, false, false, 103), "html", null, true);
                yield "</span>
            </div>
            ";
                // line 105
                if ((($tmp = ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["resource"], "counters", [], "any", false, true, false, 105), "ignored_filters", [], "any", true, true, false, 105)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["resource"], "counters", [], "any", false, false, false, 105), "ignored_filters", [], "any", false, false, false, 105), false)) : (false))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 106
                    yield "                <div class=\"sf-toolbar-info-piece\">
                    <b>Ignored Filters</b>
                    <span class=\"sf-toolbar-status sf-toolbar-status-yellow\">";
                    // line 108
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 108, $this->source); })()), "counters", [], "any", false, false, false, 108), "ignored_filters", [], "any", false, false, false, 108), "html", null, true);
                    yield "</span>
                </div>
            ";
                }
                // line 111
                yield "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['resource'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 112
            yield "    ";
            yield from [];
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 113
        yield "
    ";
        // line 114
        yield Twig\Extension\CoreExtension::include($this->env, $context, "@WebProfiler/Profiler/toolbar_item.html.twig", ["link" => true, "status" => (isset($context["status_color"]) || array_key_exists("status_color", $context) ? $context["status_color"] : (function () { throw new RuntimeError('Variable "status_color" does not exist.', 114, $this->source); })())]);
        yield "
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 117
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_menu(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "menu"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "menu"));

        // line 118
        yield "    ";
        // line 119
        yield "    <span class=\"label";
        yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 119, $this->source); })()), "resources", [], "any", false, false, false, 119)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("") : (" disabled"));
        yield "\">
        <span class=\"icon\">
            ";
        // line 121
        yield Twig\Extension\CoreExtension::include($this->env, $context, "@ApiPlatform/DataCollector/api-platform.svg");
        yield "
        </span>
        <strong>API Platform</strong>
    </span>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 127
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_panel(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "panel"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "panel"));

        // line 128
        yield "    ";
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 128, $this->source); })()), "resources", [], "any", false, false, false, 128)) == 0)) {
            // line 129
            yield "        <div class=\"metrics\">
            <div class=\"metric\">
                <span class=\"value\">Not an API Platform resource</span>
                <span class=\"label\">Resource Class</span>
            </div>
        </div>
    ";
        }
        // line 136
        yield "    ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 136, $this->source); })()), "resources", [], "any", false, false, false, 136));
        foreach ($context['_seq'] as $context["_key"] => $context["dataCollected"]) {
            // line 137
            yield "        <div class=\"metrics\">
            <div class=\"metric\">
                <span class=\"value\">";
            // line 139
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["dataCollected"], "resourceClass", [], "any", true, true, false, 139)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["dataCollected"], "resourceClass", [], "any", false, false, false, 139), "Not an API Platform resource")) : ("Not an API Platform resource")), "html", null, true);
            yield "</span>
                <span class=\"label\">Resource Class</span>
            </div>
        </div>
        ";
            // line 143
            if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["dataCollected"], "resourceMetadataCollection", [], "any", false, false, false, 143))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 144
                yield "            <h2>Resources</h2>
            <div class=\"tab-content metadata-tab-content\">
                <div class=\"sf-tabs\">
                    ";
                // line 147
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["dataCollected"], "resourceMetadataCollection", [], "any", false, false, false, 147));
                $context['loop'] = [
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                ];
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["index"] => $context["resourceMetadata"]) {
                    // line 148
                    yield "                        ";
                    $context["context_id"] = (("context-" . CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["resourceMetadata"], "resource", [], "any", false, false, false, 148), "shortName", [], "any", false, false, false, 148)) . $context["index"]);
                    // line 149
                    yield "                        <div class=\"tab\">
                            <h3 class=\"tab-title\">
                                ";
                    // line 151
                    yield ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["resourceMetadata"], "resource", [], "any", false, false, false, 151), "uriTemplate", [], "any", false, false, false, 151)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["resourceMetadata"], "resource", [], "any", false, false, false, 151), "uriTemplate", [], "any", false, false, false, 151), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["resourceMetadata"], "resource", [], "any", false, false, false, 151), "shortName", [], "any", false, false, false, 151), "html", null, true)));
                    yield "
                            </h3>
                            <div class=\"tab-content\">
                                <table>
                                    <thead>
                                        <tr>
                                            <th scope=\"col\" class=\"key\">Name</th>
                                            <th>ApiResource</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                          <td>";
                    // line 163
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["resourceMetadata"], "resource", [], "any", false, false, false, 163), "shortName", [], "any", false, false, false, 163), "html", null, true);
                    yield "</td>
                                            <td>
                                              <div>
                                                  <a class=\"btn btn-link text-small sf-toggle\" data-toggle-selector=\"#";
                    // line 166
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["context_id"]) || array_key_exists("context_id", $context) ? $context["context_id"] : (function () { throw new RuntimeError('Variable "context_id" does not exist.', 166, $this->source); })()), "html", null, true);
                    yield "\" data-toggle-alt-content=\"Hide context\">Show context</a>
                                                  <div id=\"";
                    // line 167
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["context_id"]) || array_key_exists("context_id", $context) ? $context["context_id"] : (function () { throw new RuntimeError('Variable "context_id" does not exist.', 167, $this->source); })()), "html", null, true);
                    yield "\" class=\"context sf-toggle-content sf-toggle-hidden\">";
                    // line 168
                    yield $this->extensions['Symfony\Bundle\WebProfilerBundle\Twig\WebProfilerExtension']->dumpData($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["resourceMetadata"], "resource", [], "any", false, false, false, 168), 2);
                    // line 169
                    yield "</div>
                                              </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h2>Operations</h2>
                                ";
                    // line 176
                    yield $macros["apiPlatform"]->getTemplateForMacro("macro_operationTable", $context, 176, $this->getSourceContext())->macro_operationTable(...[CoreExtension::getAttribute($this->env, $this->source, $context["resourceMetadata"], "operations", [], "any", false, false, false, 176), CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 176, $this->source); })()), "operationName", [], "any", false, false, false, 176)]);
                    yield "
                                <h2>Parameters</h2>
                                <table>
                                    <thead>
                                        <tr>
                                            <th scope=\"col\" class=\"key\">Key</th>
                                            <th scope=\"col\">Value</th>
                                            <th scope=\"col\">Parameter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ";
                    // line 187
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 187, $this->source); })()), "parameters", [], "any", false, false, false, 187)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        // line 188
                        yield "                                            ";
                        $context['_parent'] = $context;
                        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new RuntimeError('Variable "collector" does not exist.', 188, $this->source); })()), "parameters", [], "any", false, false, false, 188));
                        foreach ($context['_seq'] as $context["key"] => $context["parameter"]) {
                            // line 189
                            yield "                                                ";
                            $context["context_id"] = ("parameter-" . $context["key"]);
                            // line 190
                            yield "                                                <tr>
                                                    <td>
                                                        ";
                            // line 192
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["key"], "html", null, true);
                            yield "
                                                    </td>
                                                    <td>
                                                        ";
                            // line 195
                            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["parameter"], "extraProperties", [], "any", false, true, false, 195), "_api_values", [], "array", true, true, false, 195)) {
                                // line 196
                                yield "                                                            ";
                                yield $this->extensions['Symfony\Bridge\Twig\Extension\DumpExtension']->dump($this->env, $context, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["parameter"], "extraProperties", [], "any", false, false, false, 196), "_api_values", [], "array", false, false, false, 196));
                                yield "
                                                        ";
                            } else {
                                // line 198
                                yield "                                                            ∅
                                                        ";
                            }
                            // line 200
                            yield "                                                    </td>
                                                    <td>
                                                        <a class=\"btn btn-link text-small sf-toggle\" data-toggle-selector=\"#";
                            // line 202
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["context_id"]) || array_key_exists("context_id", $context) ? $context["context_id"] : (function () { throw new RuntimeError('Variable "context_id" does not exist.', 202, $this->source); })()), "html", null, true);
                            yield "\" data-toggle-alt-content=\"Hide context\">Show context</a>
                                                        <div id=\"";
                            // line 203
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["context_id"]) || array_key_exists("context_id", $context) ? $context["context_id"] : (function () { throw new RuntimeError('Variable "context_id" does not exist.', 203, $this->source); })()), "html", null, true);
                            yield "\" class=\"context sf-toggle-content sf-toggle-hidden\">";
                            // line 204
                            yield $this->extensions['Symfony\Bundle\WebProfilerBundle\Twig\WebProfilerExtension']->dumpData($this->env, $context["parameter"], 3);
                            // line 205
                            yield "</div>

                                                    </td>
                                                </tr>
                                            ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['key'], $context['parameter'], $context['_parent']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 210
                        yield "                                        ";
                    } else {
                        // line 211
                        yield "                                            <tr>
                                                <td class=\"text-muted\" colspan=\"2\">
                                                    No available parameters declared for this resource.
                                                </td>
                                            </tr>
                                        ";
                    }
                    // line 217
                    yield "                                    </tbody>
                                </table>
                                <h2>Filters</h2>
                                <table>
                                    <thead>
                                        <tr>
                                            <th scope=\"col\" class=\"key\">Filters</th>
                                            <th scope=\"col\"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ";
                    // line 228
                    if ((CoreExtension::getAttribute($this->env, $this->source, $context["dataCollected"], "filters", [], "any", false, false, false, 228) && CoreExtension::inFilter(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 228), Twig\Extension\CoreExtension::keys(CoreExtension::getAttribute($this->env, $this->source, $context["dataCollected"], "filters", [], "any", false, false, false, 228))))) {
                        // line 229
                        yield "                                            ";
                        $context['_parent'] = $context;
                        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["dataCollected"], "filters", [], "any", false, false, false, 229), CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 229), [], "array", false, false, false, 229));
                        $context['loop'] = [
                          'parent' => $context['_parent'],
                          'index0' => 0,
                          'index'  => 1,
                          'first'  => true,
                        ];
                        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                            $length = count($context['_seq']);
                            $context['loop']['revindex0'] = $length - 1;
                            $context['loop']['revindex'] = $length;
                            $context['loop']['length'] = $length;
                            $context['loop']['last'] = 1 === $length;
                        }
                        foreach ($context['_seq'] as $context["id"] => $context["filter"]) {
                            // line 230
                            yield "                                                ";
                            $context["ignored_filter"] = ($context["filter"] === null);
                            // line 231
                            yield "                                                <tr";
                            if ((($tmp = (isset($context["ignored_filter"]) || array_key_exists("ignored_filter", $context) ? $context["ignored_filter"] : (function () { throw new RuntimeError('Variable "ignored_filter" does not exist.', 231, $this->source); })())) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                                yield " class=\"status-warning\"";
                            }
                            yield ">
                                                    <td>
                                                        ";
                            // line 233
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["id"], "html", null, true);
                            yield "
                                                        ";
                            // line 234
                            if ((($tmp = (isset($context["ignored_filter"]) || array_key_exists("ignored_filter", $context) ? $context["ignored_filter"] : (function () { throw new RuntimeError('Variable "ignored_filter" does not exist.', 234, $this->source); })())) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                                // line 235
                                yield "                                                            <span class=\"newline text-muted\">ignored filter</span>
                                                        ";
                            } else {
                                // line 237
                                yield "                                                            ";
                                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["filter"], "html", null, true);
                                yield "
                                                        ";
                            }
                            // line 239
                            yield "                                                    </td>
                                                </tr>
                                            ";
                            ++$context['loop']['index0'];
                            ++$context['loop']['index'];
                            $context['loop']['first'] = false;
                            if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                                --$context['loop']['revindex0'];
                                --$context['loop']['revindex'];
                                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                            }
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['id'], $context['filter'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 242
                        yield "                                        ";
                    } else {
                        // line 243
                        yield "                                            <tr>
                                                <td class=\"text-muted\" colspan=\"2\">
                                                    No available filter declared for this resource.
                                                </td>
                                            </tr>
                                        ";
                    }
                    // line 249
                    yield "                                    </tbody>
                                </table>
                            </div>
                        </div>
                    ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['index'], $context['resourceMetadata'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 254
                yield "                </div>
            </div>
        ";
            }
            // line 257
            yield "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['dataCollected'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 3
    public function macro_operationLine($key = null, $operation = null, $actualOperationName = null, $n = null, ...$varargs): string|Markup
    {
        $macros = $this->macros;
        $context = [
            "key" => $key,
            "operation" => $operation,
            "actualOperationName" => $actualOperationName,
            "n" => $n,
            "varargs" => $varargs,
        ] + $this->env->getGlobals();

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
            $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "operationLine"));

            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "operationLine"));

            // line 4
            yield "    ";
            $context["context_id"] = ("operation-" . (isset($context["n"]) || array_key_exists("n", $context) ? $context["n"] : (function () { throw new RuntimeError('Variable "n" does not exist.', 4, $this->source); })()));
            // line 5
            yield "    <tr>
        <th style=\"width: 40%\" scope=\"row\"";
            // line 6
            if (((isset($context["key"]) || array_key_exists("key", $context) ? $context["key"] : (function () { throw new RuntimeError('Variable "key" does not exist.', 6, $this->source); })()) == (isset($context["actualOperationName"]) || array_key_exists("actualOperationName", $context) ? $context["actualOperationName"] : (function () { throw new RuntimeError('Variable "actualOperationName" does not exist.', 6, $this->source); })()))) {
                yield " class=\"status-success\"";
            }
            yield ">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["key"]) || array_key_exists("key", $context) ? $context["key"] : (function () { throw new RuntimeError('Variable "key" does not exist.', 6, $this->source); })()), "html", null, true);
            yield "</th>
        <td style=\"width: 60%\" ";
            // line 7
            if (((isset($context["key"]) || array_key_exists("key", $context) ? $context["key"] : (function () { throw new RuntimeError('Variable "key" does not exist.', 7, $this->source); })()) == (isset($context["actualOperationName"]) || array_key_exists("actualOperationName", $context) ? $context["actualOperationName"] : (function () { throw new RuntimeError('Variable "actualOperationName" does not exist.', 7, $this->source); })()))) {
                yield " class=\"status-success\"";
            }
            yield ">
          <a class=\"btn btn-link text-small sf-toggle\" data-toggle-selector=\"#";
            // line 8
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["context_id"]) || array_key_exists("context_id", $context) ? $context["context_id"] : (function () { throw new RuntimeError('Variable "context_id" does not exist.', 8, $this->source); })()), "html", null, true);
            yield "\" data-toggle-alt-content=\"Hide context\">Show context</a>
          <div id=\"";
            // line 9
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["context_id"]) || array_key_exists("context_id", $context) ? $context["context_id"] : (function () { throw new RuntimeError('Variable "context_id" does not exist.', 9, $this->source); })()), "html", null, true);
            yield "\" class=\"context sf-toggle-content sf-toggle-hidden\">";
            // line 10
            yield $this->extensions['Symfony\Bundle\WebProfilerBundle\Twig\WebProfilerExtension']->dumpData($this->env, (isset($context["operation"]) || array_key_exists("operation", $context) ? $context["operation"] : (function () { throw new RuntimeError('Variable "operation" does not exist.', 10, $this->source); })()), 2);
            // line 11
            yield "</div>
        </td>
    </tr>
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

            
            $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

            yield from [];
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 16
    public function macro_operationTable($object = null, $actualOperationName = null, ...$varargs): string|Markup
    {
        $macros = $this->macros;
        $context = [
            "object" => $object,
            "actualOperationName" => $actualOperationName,
            "varargs" => $varargs,
        ] + $this->env->getGlobals();

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
            $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "operationTable"));

            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "operationTable"));

            // line 17
            yield "    ";
            $macros["apiPlatform"] = $this;
            // line 18
            yield "    <table>
        <thead>
            <tr>
                <th scope=\"col\" class=\"key\">";
            // line 21
            if (array_key_exists("name", $context)) {
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::capitalize($this->env->getCharset(), (isset($context["name"]) || array_key_exists("name", $context) ? $context["name"] : (function () { throw new RuntimeError('Variable "name" does not exist.', 21, $this->source); })())), "html", null, true);
            }
            yield "Name</th>
                <th scope=\"col\">Operation</th>
            </tr>
        </thead>

        <tbody>
        ";
            // line 27
            $context["n"] = 0;
            // line 28
            yield "        ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["object"]) || array_key_exists("object", $context) ? $context["object"] : (function () { throw new RuntimeError('Variable "object" does not exist.', 28, $this->source); })()));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["key"] => $context["itemOperation"]) {
                // line 29
                yield "          ";
                $context["n"] = ((isset($context["n"]) || array_key_exists("n", $context) ? $context["n"] : (function () { throw new RuntimeError('Variable "n" does not exist.', 29, $this->source); })()) + 1);
                // line 30
                yield "          ";
                yield $macros["apiPlatform"]->getTemplateForMacro("macro_operationLine", $context, 30, $this->getSourceContext())->macro_operationLine(...[$context["key"], $context["itemOperation"], (isset($context["actualOperationName"]) || array_key_exists("actualOperationName", $context) ? $context["actualOperationName"] : (function () { throw new RuntimeError('Variable "actualOperationName" does not exist.', 30, $this->source); })()), (isset($context["n"]) || array_key_exists("n", $context) ? $context["n"] : (function () { throw new RuntimeError('Variable "n" does not exist.', 30, $this->source); })())]);
                yield "
        ";
                $context['_iterated'] = true;
            }
            // line 31
            if (!$context['_iterated']) {
                // line 32
                yield "            <tr>
                <td colspan=\"2\" class=\"text-muted\">
                    No available ";
                // line 34
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::lower($this->env->getCharset(), (isset($context["name"]) || array_key_exists("name", $context) ? $context["name"] : (function () { throw new RuntimeError('Variable "name" does not exist.', 34, $this->source); })())), "html", null, true);
                yield " operation for this resource.
                </td>
            </tr>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['key'], $context['itemOperation'], $context['_parent'], $context['_iterated']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 38
            yield "        </tbody>
    </table>
";
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

            
            $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

            yield from [];
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 42
    public function macro_providerTable($object = null, $name = null, ...$varargs): string|Markup
    {
        $macros = $this->macros;
        $context = [
            "object" => $object,
            "name" => $name,
            "varargs" => $varargs,
        ] + $this->env->getGlobals();

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
            $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "providerTable"));

            $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "macro", "providerTable"));

            // line 43
            yield "    ";
            if (Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, (isset($context["object"]) || array_key_exists("object", $context) ? $context["object"] : (function () { throw new RuntimeError('Variable "object" does not exist.', 43, $this->source); })()), "responses", [], "any", false, false, false, 43))) {
                // line 44
                yield "        <div class=\"empty\">
            <p>No calls to ";
                // line 45
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["name"]) || array_key_exists("name", $context) ? $context["name"] : (function () { throw new RuntimeError('Variable "name" does not exist.', 45, $this->source); })()), "html", null, true);
                yield " have been recorded.</p>
        </div>
    ";
            } else {
                // line 48
                yield "        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Used</th>
                <th>";
                // line 53
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::capitalize($this->env->getCharset(), (isset($context["name"]) || array_key_exists("name", $context) ? $context["name"] : (function () { throw new RuntimeError('Variable "name" does not exist.', 53, $this->source); })())), "html", null, true);
                yield "</th>
            </tr>
            </thead>
            <tbody>
            ";
                // line 57
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["object"]) || array_key_exists("object", $context) ? $context["object"] : (function () { throw new RuntimeError('Variable "object" does not exist.', 57, $this->source); })()), "responses", [], "any", false, false, false, 57));
                $context['loop'] = [
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                ];
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["class"] => $context["used"]) {
                    // line 58
                    yield "                <tr>
                    <td class=\"font-normal text-small text-muted nowrap\">";
                    // line 59
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index", [], "any", false, false, false, 59), "html", null, true);
                    yield "</td>
                    <td class=\"font-normal\">
                        ";
                    // line 61
                    if (($context["used"] === true)) {
                        // line 62
                        yield "                            <span class=\"label status-success same-width\">TRUE</span>
                        ";
                    } elseif ((                    // line 63
$context["used"] === false)) {
                        // line 64
                        yield "                            <span class=\"label status-error same-width\">FALSE</span>
                        ";
                    } else {
                        // line 66
                        yield "                            <span class=\"label status-info same-width\">NOT USED</span>
                        ";
                    }
                    // line 68
                    yield "                    </td>
                    <td class=\"font-normal\">";
                    // line 69
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["class"], "html", null, true);
                    yield "</td>
                </tr>
            ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['class'], $context['used'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 72
                yield "            </tbody>
            ";
                // line 73
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["object"] ?? null), "context", [], "any", true, true, false, 73)) {
                    // line 74
                    yield "                <tfoot>
                    <tr>
                        <td class=\"font-normal\" colspan=\"2\"></td>
                        <td class=\"font-normal\">Context";
                    // line 77
                    yield $this->extensions['Symfony\Bundle\WebProfilerBundle\Twig\WebProfilerExtension']->dumpData($this->env, CoreExtension::getAttribute($this->env, $this->source, (isset($context["object"]) || array_key_exists("object", $context) ? $context["object"] : (function () { throw new RuntimeError('Variable "object" does not exist.', 77, $this->source); })()), "context", [], "any", false, false, false, 77), 2);
                    yield "</td>
                    </tr>
                </tfoot>
            ";
                }
                // line 81
                yield "        </table>
    ";
            }
            
            $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

            
            $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

            yield from [];
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "@ApiPlatform/DataCollector/request.html.twig";
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
        return array (  787 => 81,  780 => 77,  775 => 74,  773 => 73,  770 => 72,  753 => 69,  750 => 68,  746 => 66,  742 => 64,  740 => 63,  737 => 62,  735 => 61,  730 => 59,  727 => 58,  710 => 57,  703 => 53,  696 => 48,  690 => 45,  687 => 44,  684 => 43,  665 => 42,  651 => 38,  641 => 34,  637 => 32,  635 => 31,  628 => 30,  625 => 29,  619 => 28,  617 => 27,  606 => 21,  601 => 18,  598 => 17,  579 => 16,  564 => 11,  562 => 10,  559 => 9,  555 => 8,  549 => 7,  541 => 6,  538 => 5,  535 => 4,  514 => 3,  499 => 257,  494 => 254,  476 => 249,  468 => 243,  465 => 242,  449 => 239,  443 => 237,  439 => 235,  437 => 234,  433 => 233,  425 => 231,  422 => 230,  404 => 229,  402 => 228,  389 => 217,  381 => 211,  378 => 210,  368 => 205,  366 => 204,  363 => 203,  359 => 202,  355 => 200,  351 => 198,  345 => 196,  343 => 195,  337 => 192,  333 => 190,  330 => 189,  325 => 188,  323 => 187,  309 => 176,  300 => 169,  298 => 168,  295 => 167,  291 => 166,  285 => 163,  270 => 151,  266 => 149,  263 => 148,  246 => 147,  241 => 144,  239 => 143,  232 => 139,  228 => 137,  223 => 136,  214 => 129,  211 => 128,  198 => 127,  182 => 121,  176 => 119,  174 => 118,  161 => 117,  148 => 114,  145 => 113,  141 => 112,  135 => 111,  129 => 108,  125 => 106,  123 => 105,  118 => 103,  114 => 101,  109 => 100,  102 => 95,  99 => 94,  97 => 93,  94 => 92,  87 => 90,  84 => 89,  81 => 88,  68 => 87,  57 => 1,  55 => 85,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends '@WebProfiler/Profiler/layout.html.twig' %}

{% macro operationLine(key, operation, actualOperationName, n) %}
    {% set context_id = 'operation-' ~ n %}
    <tr>
        <th style=\"width: 40%\" scope=\"row\"{% if key == actualOperationName %} class=\"status-success\"{% endif %}>{{ key }}</th>
        <td style=\"width: 60%\" {% if key == actualOperationName %} class=\"status-success\"{% endif %}>
          <a class=\"btn btn-link text-small sf-toggle\" data-toggle-selector=\"#{{ context_id }}\" data-toggle-alt-content=\"Hide context\">Show context</a>
          <div id=\"{{ context_id }}\" class=\"context sf-toggle-content sf-toggle-hidden\">
              {{- profiler_dump(operation, 2) -}}
          </div>
        </td>
    </tr>
{% endmacro %}

{% macro operationTable(object, actualOperationName) %}
    {% import _self as apiPlatform %}
    <table>
        <thead>
            <tr>
                <th scope=\"col\" class=\"key\">{% if name is defined %}{{ name|capitalize }}{% endif %}Name</th>
                <th scope=\"col\">Operation</th>
            </tr>
        </thead>

        <tbody>
        {% set n = 0 %}
        {% for key, itemOperation in object %}
          {% set n = n + 1 %}
          {{ apiPlatform.operationLine(key, itemOperation, actualOperationName, n) }}
        {% else %}
            <tr>
                <td colspan=\"2\" class=\"text-muted\">
                    No available {{ name|lower }} operation for this resource.
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endmacro %}

{% macro providerTable(object, name) %}
    {% if object.responses is empty %}
        <div class=\"empty\">
            <p>No calls to {{ name }} have been recorded.</p>
        </div>
    {% else %}
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Used</th>
                <th>{{ name|capitalize }}</th>
            </tr>
            </thead>
            <tbody>
            {% for class, used in object.responses %}
                <tr>
                    <td class=\"font-normal text-small text-muted nowrap\">{{ loop.index }}</td>
                    <td class=\"font-normal\">
                        {% if used is same as(true) %}
                            <span class=\"label status-success same-width\">TRUE</span>
                        {% elseif used is same as(false) %}
                            <span class=\"label status-error same-width\">FALSE</span>
                        {% else %}
                            <span class=\"label status-info same-width\">NOT USED</span>
                        {% endif %}
                    </td>
                    <td class=\"font-normal\">{{ class }}</td>
                </tr>
            {% endfor %}
            </tbody>
            {% if object.context is defined %}
                <tfoot>
                    <tr>
                        <td class=\"font-normal\" colspan=\"2\"></td>
                        <td class=\"font-normal\">Context{{ profiler_dump(object.context, 2) }}</td>
                    </tr>
                </tfoot>
            {% endif %}
        </table>
    {% endif %}
{% endmacro %}

{% import _self as apiPlatform %}

{% block toolbar %}
    {% set icon %}
        {% set status_color = collector.counters.ignored_filters|default(false) ? 'yellow' : 'default' %}
        {{ include('@ApiPlatform/DataCollector/api-platform-icon.svg') }}
    {% endset %}

    {% set text %}
        {% if collector.resources|length == 0 %}
            <div class=\"sf-toolbar-info-piece\">
                <b>Resource Class</b>
                <span>Not an API Platform resource</span>
            </div>
        {% endif %}
        {% for resource in collector.resources %}
            <div class=\"sf-toolbar-info-piece\">
                <b>Resource Class</b>
                <span>{{ resource.resourceClass }}</span>
            </div>
            {% if resource.counters.ignored_filters|default(false) %}
                <div class=\"sf-toolbar-info-piece\">
                    <b>Ignored Filters</b>
                    <span class=\"sf-toolbar-status sf-toolbar-status-yellow\">{{ collector.counters.ignored_filters }}</span>
                </div>
            {% endif %}
        {% endfor %}
    {% endset %}

    {{ include('@WebProfiler/Profiler/toolbar_item.html.twig', { 'link': true, status: status_color }) }}
{% endblock %}

{% block menu %}
    {# This left-hand menu appears when using the full-screen profiler. #}
    <span class=\"label{{ collector.resources ? '' : ' disabled' }}\">
        <span class=\"icon\">
            {{ include('@ApiPlatform/DataCollector/api-platform.svg') }}
        </span>
        <strong>API Platform</strong>
    </span>
{% endblock %}

{% block panel %}
    {% if collector.resources|length == 0 %}
        <div class=\"metrics\">
            <div class=\"metric\">
                <span class=\"value\">Not an API Platform resource</span>
                <span class=\"label\">Resource Class</span>
            </div>
        </div>
    {% endif %}
    {% for dataCollected in collector.resources %}
        <div class=\"metrics\">
            <div class=\"metric\">
                <span class=\"value\">{{ dataCollected.resourceClass|default('Not an API Platform resource') }}</span>
                <span class=\"label\">Resource Class</span>
            </div>
        </div>
        {% if dataCollected.resourceMetadataCollection is not empty %}
            <h2>Resources</h2>
            <div class=\"tab-content metadata-tab-content\">
                <div class=\"sf-tabs\">
                    {% for index, resourceMetadata in dataCollected.resourceMetadataCollection %}
                        {% set context_id = 'context-' ~ resourceMetadata.resource.shortName ~ index %}
                        <div class=\"tab\">
                            <h3 class=\"tab-title\">
                                {{ resourceMetadata.resource.uriTemplate ?: resourceMetadata.resource.shortName }}
                            </h3>
                            <div class=\"tab-content\">
                                <table>
                                    <thead>
                                        <tr>
                                            <th scope=\"col\" class=\"key\">Name</th>
                                            <th>ApiResource</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                          <td>{{ resourceMetadata.resource.shortName }}</td>
                                            <td>
                                              <div>
                                                  <a class=\"btn btn-link text-small sf-toggle\" data-toggle-selector=\"#{{ context_id }}\" data-toggle-alt-content=\"Hide context\">Show context</a>
                                                  <div id=\"{{ context_id }}\" class=\"context sf-toggle-content sf-toggle-hidden\">
                                                      {{- profiler_dump(resourceMetadata.resource, 2) -}}
                                                  </div>
                                              </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h2>Operations</h2>
                                {{ apiPlatform.operationTable(resourceMetadata.operations, collector.operationName) }}
                                <h2>Parameters</h2>
                                <table>
                                    <thead>
                                        <tr>
                                            <th scope=\"col\" class=\"key\">Key</th>
                                            <th scope=\"col\">Value</th>
                                            <th scope=\"col\">Parameter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% if collector.parameters %}
                                            {% for key, parameter in collector.parameters %}
                                                {% set context_id = 'parameter-' ~ key %}
                                                <tr>
                                                    <td>
                                                        {{ key }}
                                                    </td>
                                                    <td>
                                                        {% if parameter.extraProperties['_api_values'] is defined %}
                                                            {{ dump(parameter.extraProperties['_api_values']) }}
                                                        {% else %}
                                                            ∅
                                                        {% endif %}
                                                    </td>
                                                    <td>
                                                        <a class=\"btn btn-link text-small sf-toggle\" data-toggle-selector=\"#{{ context_id }}\" data-toggle-alt-content=\"Hide context\">Show context</a>
                                                        <div id=\"{{ context_id }}\" class=\"context sf-toggle-content sf-toggle-hidden\">
                                                          {{- profiler_dump(parameter, 3) -}}
                                                        </div>

                                                    </td>
                                                </tr>
                                            {% endfor %}
                                        {% else %}
                                            <tr>
                                                <td class=\"text-muted\" colspan=\"2\">
                                                    No available parameters declared for this resource.
                                                </td>
                                            </tr>
                                        {% endif %}
                                    </tbody>
                                </table>
                                <h2>Filters</h2>
                                <table>
                                    <thead>
                                        <tr>
                                            <th scope=\"col\" class=\"key\">Filters</th>
                                            <th scope=\"col\"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {% if dataCollected.filters and loop.index0 in dataCollected.filters|keys %}
                                            {% for id, filter in dataCollected.filters[loop.index0] %}
                                                {% set ignored_filter = filter is same as(null) %}
                                                <tr{% if ignored_filter %} class=\"status-warning\"{% endif %}>
                                                    <td>
                                                        {{ id }}
                                                        {% if ignored_filter %}
                                                            <span class=\"newline text-muted\">ignored filter</span>
                                                        {% else %}
                                                            {{ filter }}
                                                        {% endif %}
                                                    </td>
                                                </tr>
                                            {% endfor %}
                                        {% else %}
                                            <tr>
                                                <td class=\"text-muted\" colspan=\"2\">
                                                    No available filter declared for this resource.
                                                </td>
                                            </tr>
                                        {% endif %}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    {% endfor %}
                </div>
            </div>
        {% endif %}
    {% endfor %}
{% endblock %}
", "@ApiPlatform/DataCollector/request.html.twig", "C:\\Users\\leona\\PhpstormProjects\\CYNA-Web\\vendor\\api-platform\\symfony\\Bundle\\Resources\\views\\DataCollector\\request.html.twig");
    }
}
