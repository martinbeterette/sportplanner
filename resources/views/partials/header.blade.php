<header>
    <nav>
        <ul>
            {% for option in menuOptions %}
                <li><a href="{{ base_url }}{{ option.url }}">{{ option.name }}</a></li>
            {% endfor %}
        </ul>
    </nav>
</header>