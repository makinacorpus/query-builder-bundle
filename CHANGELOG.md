# Changelog

## 1.0.0

* [feature] ⭐️ Container will autowire any `MakinaCorpus\QueryBuilder\QueryBuilder`
  or `MakinaCorpus\QueryBuilder\DatabaseSession` argument using the parameter
  name as the connection name. For example naming the argument `$default` will
  inject the database session using the homonymous doctrine connection name.
  If no match found, session using the default Doctrine connection will be
  injected instead.
* [feature] ⭐️ Services are now identifier as `query_builder.session.CONNECTION`
  and can be typed as either `MakinaCorpus\QueryBuilder\QueryBuilder` or
  `MakinaCorpus\QueryBuilder\DatabaseSession`.
* [deprecation] ⚠️ Services identified as `query_builder.doctrine.CONNECTION`
  are deprecated and will be rmeoved in next major.
* [internal] Raise `makinacorpus/query-builder` dependency to version 1.6.1.

## 0.1.3

Initial release.

* [feature] ⭐️ Compatibility with Symfony 7.0.
* [feature] ⭐️ Compatibility with Symfony 6.0.
