# ProGram - LBAW2411

> Curricular Unit: [Database and Web Applications Laboratory - 2024/25 1S](https://sigarra.up.pt/feup/en/ucurr_geral.ficha_uc_view?pv_ocorrencia_id=541888)<br>
> Faculty: [FEUP](https://sigarra.up.pt/feup/en/web_page.Inicial)<br>
> Professor: [Sérgio Nunes](https://sigarra.up.pt/feup/en/func_geral.formview?p_codigo=310021)<br>
> Authors: [Bruno Oliveira](https://github.com/Process-ing), [Henrique Fernandes](https://github.com/HenriqueSFernandes), [José Sousa](https://github.com/jose-carlos-sousa), [Rodrigo Silva](https://github.com/racoelhosilva)<br>
> Grades:<br>
> &emsp; [ER Component](https://github.com/Process-ing/feup-lbaw/wiki/er): 97%<br>
> &emsp; [EBD Component](https://github.com/Process-ing/feup-lbaw/wiki/ebd): 98%<br>
> &emsp; [EAP Component](https://github.com/Process-ing/feup-lbaw/wiki/eap): 98%<br>
> &emsp; [PA Component](https://github.com/Process-ing/feup-lbaw/wiki/pa): 93%<br>
> Final Grade: 19.2/20

## ProGram

ProGram is a social networking platform being developed by a startup which aims to connect programmers with like-minded individuals. The need for a specialized space where technology professionals can connect, collaborate, and share knowledge drives this initiative. By providing an interactive and supportive environment, ProGram seeks to fill this gap within the tech community.

## Installation

In order to run the project, it is necessary to login into gitlab.up.pt using docker:

```sh
docker login gitlab.up.pt:5050
```

After that, the project is available by running the following command to download and run the container:

```sh
docker run -d --name lbaw2411 -p 8001:80 gitlab.up.pt:5050/lbaw/lbaw2425/lbaw2411
```

This should make the website available at `localhost:8001`.

## Usage

### Administration Credentials

> Administration URL: http://localhost:8001/admin

| E-mail               | Password   |
| -------------------- | ---------- |
| kherrera@program.com | AdminsRule |

### User Credentials

| Type | Username              | Password     |
| ---- | --------------------- | ------------ |
| User | cindyburton@gmail.com | ILoveProgram |

### Password Recovery (Mailtrap)

> Mailtrap URL: https://mailtrap.io/inboxes/3327402/messages

**Login with the Google account:**

| E-mail                | Password        |
| --------------------- | --------------- |
| programlbaw@gmail.com | programlbaw2411 |

### Real-Time Notifications (Pusher)

> Pusher URL: https://dashboard.pusher.com/apps/1909573

**Login with the Google account:**

| E-mail                | Password        |
| --------------------- | --------------- |
| programlbaw@gmail.com | programlbaw2411 |

### External API (Postman)

We developed an external API which users can access with a generated token.  
The API reference is located here: http://127.0.0.1:8001/apireference.
Tokens can be generated from the user settings or by filling this link: http://127.0.0.1:8001/user/{user-id}/token.  

To use the API, we provide the following Postman collection: [ProGram API](https://www.postman.com/programlbaw/workspace/program-api/collection/40575933-cbac648a-3f42-4ed8-ba1f-5ce20919677d?action=share&creator=40575933)

Alternatively, you can use the following Curl command structure to access the API:

```sh
curl -X GET "http://localhost:8001/api/<ROUTE>" \
    -H "Authorization: Bearer <ACCESS_TOKEN>"
```

## Learn More

In order to learn more about our project, we encourage you to check the following documents:
* [Main wiki page](https://github.com/Process-ing/feup-lbaw/wiki)
* [ER: Requirements Specification](https://github.com/Process-ing/feup-lbaw/wiki/er)
* [EBD: Database Specification](https://github.com/Process-ing/feup-lbaw/wiki/ebd)
* [EAP: Architecture Specification and Prototype](https://github.com/Process-ing/feup-lbaw/wiki/eap)
* [PA: Product and Presentation](https://github.com/Process-ing/feup-lbaw/wiki/pa)


## Tips and Tricks (for anyone doing a similar project)

- Some of the professors complained about the delivered (PDF) wikis not having selectable text. This is a problem with the "Print..." action in Firefox. Make sure to use a chromium-based browser (like Google Chrome) to print the wiki pages.
- For the wiki, you will have to write a lot of markdown, with a lot of tables. I recommend to use a formatter to keep everything in the tables aligned and tidy, like [this extension for VSCode](https://marketplace.visualstudio.com/items?itemName=yzhang.markdown-all-in-one). Since the first two deliveries are the ones that require more things in the wiki, probably requiring a lot of collaboration, which is not feasible by using Liveshare alone, it may be wise to write everything in a Notion page and then copy it to the wiki.
- Most of the diagrams you need to create are not very sophisticated, so the best tool for the job is [draw.io](https://app.diagrams.net/). Although we also created our wireframes in draw.io, it was a bit of a hassle, so I recommend using [Figma](https://www.figma.com/) instead.
- When doing the EBD component, more specifically the conceptual model, I would highly recommend to first make a draft of the model in paper, and then write a sharper version in draw.io by dividing the model into logical parts (classes/relationships related to posts, related to notifications, related to users, ...) and then join them all in the end. The diagram will be much easier to organize, trust me.
- As we did, it is a good idea to define your website's style in the EAP component. Work a little bit in Figma to decide on the style you'll like (e.g. for the home page), trying to use as little different components as possible, and implement the components in the prototype as separate views. This will make your website's look and feel much more consistent, and you will not have to worry about it later in the PA component.
- The parts that require the most work are objectively the EAP and PA components. For the PA specifically, try to have all features implemented before the last week. Trust me, you will still need a lot of time to fix HTML/CSS validation errors, usability and accessibility issues, make pages responsive, write the wiki and fix bugs (which will be much more severe than in the EAP component if left untreated!)

## Team

* Bruno Ricardo Soares Pereira de Sousa Oliveira, up202208700@up.pt
* Henrique Sardo Fernandes, up202204988@up.pt
* José Carlos Malheiro de Sousa, up202208817@up.pt
* Rodrigo Albergaria Coelho e Silva, up202205188@up.pt

***
GROUP2411, December 2024