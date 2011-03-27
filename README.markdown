## Project triage

a personal statement:
> This project is for personal purpose. it is to show best practices vs bad practice. but instead of rebuilding a project, improve the code which total lacks principles. The first commit is a direct copy from the project as found on an backup dated back to 2008. The level of the source code in this project should not be mistaken for the skill and the experience i've gained until today


### Very First project


at that time it was a well design to me (from the outside). the source code (lets not consider calling this even code yet)  is [the oposite of OO](http://stackoverflow.com/questions/5400585/the-opposite-of-object-oriented "a question on stackoverflow"). Its php tags everywhere mixed with html, allot of echo (and print) statements, a few function and if thats not enough there is a few sql queries placed very random between the lines. Did I know better?  I don't know..


> i can remember *laughing my ass of* with my school mates when I explained the structure and having new php file for every page and so on.


So yes at that time I did not know very better. Maybe a few weeks later, but the project was not worth working on. As we coders do, we moved on to the next ;)

I hear you ask: So why did you put it on github then?

#### Its a nice use case. to show of skill

> Believe that making improvements and moving on goes faster than redoing the whole thing.

The whole idea of putting this on github and actually doing this is very much inspired by a talk ["Project Triage and Recovery"](http://caseysoftware.com/blog/phpbenelux-2011-recap) by [@caseysoftware](https://twitter.com/caseysoftware, "on twitter")
But ofcourse in my case there is no real problem. The project is has no huge complex codebase, no deadlines etc
therefore this could become very boring quickly, yes.
And to make things interesting I've put some rules:

*   benchmark on a regular basis
*   list stats (phploc, phpcpd, CodeSniffer etc) about the health and growth of the code base every x commits.
*   make a list of all the bad practices (technical debt paradigm).
*   consider how to keep improvements cheap and realistic. that means nothing to radical should be done in one commit and everything should still work as it works now.
*   new features must be added in the mean time and should not be mixed with mayor refactoring commits. (to make it more realistic)


 * * *

     DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
     Version 2, December 2004

     Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

     Everyone is permitted to copy and distribute verbatim or modified
     copies of this license document, and changing it is allowed as long
     as the name is changed.

     DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
     TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

     \0. You just DO WHAT THE FUCK YOU WANT TO.

