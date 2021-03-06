[![Maintainability](https://api.codeclimate.com/v1/badges/1dbd9fd6a6f96a845658/maintainability)](https://codeclimate.com/github/LarsNieuwenhuizen/Nieuwenhuizen.ContentSecurityPolicy/maintainability)
[![StyleCI](https://github.styleci.io/repos/143305157/shield?branch=master)](https://github.styleci.io/repos/143305157)

# Nieuwenhuizen.ContentSecurityPolicy
Flow/Neos package to set your site's content security policy header easily with yaml

- [Usage](#usage)
- [Custom directives](#custom)
- [Disable or report](#disable-report)
- [Nonce](#nonce)
- [Backend](#backend)

## <a name="usage"></a>Usage

Import the package using composer:

```bash
composer require nieuwenhuizen/content-security-policy
```

The package is automatically active once imported.
By default the response header `Content-Security-Policy` will now be included.

It will use the default configuration which looks like this:

```yaml
Nieuwenhuizen:
  ContentSecurityPolicy:
      enabled: true
      report-only: false
      content-security-policy:
        default:
          base-uri:
            - 'self'
          connect-src:
            - 'self'
          default-src:
            - 'self'
          form-action:
            - 'self'
          img-src:
            - 'self'
          media-src:
            - 'self'
          frame-src:
            - 'self'
          object-src:
            - 'self'
          script-src:
            - 'self'
          style-src:
            - 'self'
          font-src:
            - 'self'
        custom: []
```

Now only resources from the same origin are allowed for the most common directives.
It is enabled by default and the report-only mode is disabled.

## <a name="custom"></a>Custom directives and values

The default configuration will probably not suit your needs so you can add your own configuration by adding the array custom like this in your own yaml configuration files:

```yaml
Nieuwenhuizen:
  ContentSecurityPolicy:
    content-security-policy:
      custom:
        frame-src:
          - 'https://www.youtube.com'
          - 'https://staticxx.facebook.com'
```

If you fully want to override the entire default config then just override the default key in yaml.

## <a name="disable-report"></a>Disable or report only

To disable the header simply set `enabled` to false.
If you want to add it as a report only header set `report-only` to true.
That way you have the option to see the possible errors without breaking functionality.

## <a name="nonce"></a>Nonce

You might want to use a nonce to allow inline scripts and styles to be still secure.
To do this simply add `{nonce}` as an option in a directive. Like this:

```yaml
Nieuwenhuizen:
  ContentSecurityPolicy:
    content-security-policy:
      custom:
        script-src:
          - '{nonce}'
```

Now the header will include a `nonce-automatedgeneratedrandomstring` in the script-src directive.
So inline scripts without the corresponding nonce will be blocked.

To add the nonce string in your templates use the supplied ViewHelper like this:

```html
{namespace csp=Nieuwenhuizen\ContentSecurityPolicy\ViewHelpers}

<script nonce="{csp:nonce()}">
	alert('Hello world');
</script>
```

## <a name="backend"></a>Backend

Do to the current nature of the Neos backend being rendered a bit different then the frontend a separate policy is added for the backend.
I currently have found no suitable way the add the nonce in the inline scripts in the Neos UI package.
So the CSP for the backend looks like this:

```yaml
backend:
  base-uri:
    - 'self'
  connect-src:
    - 'self'
  default-src:
    - 'self'
  form-action:
    - 'self'
  img-src:
    - 'self'
  media-src:
    - 'self'
  frame-src:
    - 'self'
  object-src:
    - 'self'
  script-src:
    - 'self'
    - 'unsafe-inline'
  style-src:
    - 'self'
    - 'unsafe-inline'
  font-src:
    - 'self'
custom-backend: []
```

Unsafe inline scripts and styles are allowed in the backend because otherwise the backend won't work.
I will try to find a nice solution for this as soon as possible.

Again you can add your own policies in the custom-backend array the same way as the custom array for the frontend.
