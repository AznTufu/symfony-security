<h4> Attaques XSS : </h4>

```php
    public function setName(string $name): static {
          $this->name = htmlspecialchars($name);
  
          return $this;
    }
  
   <h3>{{ keyboard.name|raw }}</h3>

```

<h4> Attaques CSRF : </h4>

```php
    #[Route('/keyboard/delete/{id}', name: 'app_keyboard_delete')]
    public function delete(Request $r, EntityManagerInterface $em, Keyboard $keyboard) {
        if($this->isCsrfTokenValid('delete'.$keyboard->getId(), $r->request->get('csrf'))){
            $em->remove($keyboard);
            $em->flush();
        }

        return $this->redirectToRoute('app_keyboard');
    }

    <form action="{{ path('app_keyboard_delete', {'id': keyboard.id}) }}" method="POST">
        <input type="hidden" name="csrf" value={{csrf_token('delete' ~ keyboard.id)}}>
        <input type="submit" value="Supprimer">
    </form>
```

<h4> Slug dans l'url : </h4>

```php
    #[Route('/keyboard/{slug}', name: 'app_keyboard_show')]
    public function show(Keyboard $keyboard): Response {
        return $this->render('keyboard/show.html.twig', [
            'keyboard' => $keyboard
        ]);
    }
```

<h4> Hacher les mots de passe : </h4>

```php
     $user->setPassword(
          $this->userPasswordHasher->hashPassword(
              $user,
              'password'
          )
      );
```

<h4> Injections SQL : </h4>
Comment les contrer :

```
- Avoir des utilisateurs SQL avec des droits limités
- Utiliser des requêtes préparées
```
