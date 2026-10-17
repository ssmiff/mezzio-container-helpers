# ssmiff/mezzio-container-helpers

Attribute-driven constructor injection for Laminas service manager factories, with per-parameter overrides.

Requires PHP 8.5. Licensed under the [MIT licence](LICENSE).

## Install

```
composer require ssmiff/mezzio-container-helpers
```

`laminas-component-installer` registers `Ssmiff\MezzioContainerHelpers\ConfigProvider`, which provides the `ConstructorResolver` service.

## Attributes

A constructor parameter gets its value from, in order:

1. an override passed to the plan, keyed by the parameter's name,
2. a `ParameterSource` attribute on the parameter,
3. its class type, fetched from the container,
4. its default value.

```php
final readonly class ReportMailer
{
    public function __construct(
        #[UseEntityManager('reporting')]
        private EntityManagerInterface $entityManager,
        #[ConfigValue('mail.from')]
        private string $from,
        private string $apiKey,
        private ClockInterface $clock,
        private int $retries = 3,
    ) {
    }
}
```

`#[ConfigValue('app.audit_actor', 'system')]` reads a dotted path from the merged `config` service and falls back to the second argument.
`UseEntityManager` is from `ssmiff/mezzio-doctrine`.

## AttributeFactory

When every parameter can come from attributes, types or defaults, register the class with `AttributeFactory`:

```php
'dependencies' => ['factories' => [RecordPageViewHandler::class => AttributeFactory::class]],
```

## Custom factories

When some values are made up in the factory, ask the resolver for the class's plan and pass them by parameter name:

```php
final readonly class ReportMailerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array|null $options = null): ReportMailer
    {
        return Services::get($container, ConstructorResolver::class)
            ->plan(ReportMailer::class)
            ->instantiate($container, ['apiKey' => $this->makeUpAKey($container)]);
    }
}
```

A parameter that none of the sources covers throws, naming the parameter. An override for a name that is not a parameter also throws, which catches typos.

`$plan->arguments($container, $overrides)` returns the same values as an array keyed by parameter name, if you want to call the constructor yourself.

Plans are cached in memory for the life of the process, so reflection runs once per class.

## Your own attributes

Implement `ParameterSource`:

```php
#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class UseTenantDatabase implements ParameterSource
{
    public function resolve(ContainerInterface $container): mixed
    {
        return $container->get('doctrine.entity_manager.tenant');
    }
}
```

## Services::get

`Services::get($container, Foo::class)` fetches a service and checks its type, so static analysers see `Foo` rather than `mixed`.
An optional third argument gives the service id when it differs from the class: `Services::get($container, Foo::class, 'foo.special')`.

## Development

```
composer install
composer check
```
