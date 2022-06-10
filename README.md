# Slack Unfurl Bundle

Unfurl Slack urls with help of [Symfony Messenger](https://symfony.com/doc/current/messenger.html)

## Integrations
### Gitlab
With Gitlab client by [Zeichen32GitLabApiBundle](https://github.com/Zeichen32/GitLabApiBundle)

## Example Usage
See example app: [spatricius/slack-unfurl](https://github.com/spatricius/slack-unfurl)

## Example Slack configuration
Example manifest in [Slack Apps](https://api.slack.com/apps)

```yaml
display_information:
  name: My unfurl app
  description: Show links desciptions
  background_color: "#000000"
features:
  bot_user:
    display_name: Links helper
    always_online: false
  unfurl_domains:
    - gitlab.mydomain.com
oauth_config:
  scopes:
    bot:
      - links:read
      - links:write
settings:
  event_subscriptions:
    request_url: http://111.111.111.111:666/unfurl/
    bot_events:
      - link_shared
  org_deploy_enabled: false
  socket_mode_enabled: false
  token_rotation_enabled: false
```
