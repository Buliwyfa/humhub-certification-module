[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GreenVolume/member-certification-/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/GreenVolume/member-certification-/?branch=dev) [![Code Coverage](https://scrutinizer-ci.com/g/GreenVolume/member-certification-/badges/coverage.png?b=dev)](https://scrutinizer-ci.com/g/GreenVolume/member-certification-/?branch=dev) [![Join the chat at https://gitter.im/GreenVolume](https://badges.gitter.im/GreenVolume.svg)](https://gitter.im/GreenVolume?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## HumhubCertifiedRealModule

This is a Humhub module where admins can certify the person as being a real person when they upload a picture of themselves with the site name and their screen name.

This module will give the option of having members certified as real people to cut down on spammers. The users will upload a picture of themselves holding a sign with the site name and their username on it. Once the picture has been uploaded they will become temporarily verified till one of the admins views the pictures and then certifies them as real.

Still in development. Please feel free to fork and assist in the process if you'd like.

With the Custom Mail Module. This module gives admin the ability to control who has mail privileges based on the group they are located in. This module will also automatically move the user from the uncertified group to the certified group upon image upload. They will then still need to be verified by an admin.

Screen Shoot one.

<img src="https://github.com/GreenVolume/humhub-certification-module/blob/master/assets/screenshots/certified.JPG?raw=true" alt="Smiley face" height="auto" width="600">

User doesn't have mail permission. No mail button to click.

Screen Shoot two.


<img src="https://github.com/GreenVolume/humhub-certification-module/blob/master/assets/screenshots/certified1.JPG?raw=true" alt="Smiley face" height="auto" width="600">

User just uploaded picture. Now has mail prmission. Mail button.

Screen Shoot three.

<img src="https://github.com/GreenVolume/humhub-certification-module/blob/master/assets/screenshots/certified2.JPG?raw=true" alt="Smiley face" height="auto" width="600">

This user has been granted permission to access the certify module page to certify users after they have uploaded a picture.

Screen Shoot four.

<img src="https://github.com/GreenVolume/humhub-certification-module/blob/master/assets/screenshots/certified3.JPG?raw=true" alt="Smiley face" height="auto" width="600">

This is currently what the certified admin page looks like to certify a user as real. It needs more cosmetics done to it and will be done in the future. I'm not worried about how the admin section looks right now. Just that it works. Which it doesn't all the way at this current time.

The accept and Deny Button work and the Delete Button Takes you to the admin page where the user can be deleted from the community. However, once the user has been denied. The module revokes the users mail privileges again. Once the user submits another picture for certification, they no longer receive automatic permissions to mail.

However in order to verify the user as real again, you need to go into the records because for some reason, (still working on this bug) the new record doesn't save and therefore doesn't get added to the users needing permissions admin page.
