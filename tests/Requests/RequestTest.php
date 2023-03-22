<?php

namespace Tests\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use SlothDevGuy\MVCEnhancements\Http\SimpleRequest;
use Tests\TestCase;

/**
 * Class RequestTest
 * @package Tests\Requests
 */
class RequestTest extends TestCase
{
    /**
     * @return void
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function testValidation() : void
    {
        $getRequest = $this->buildRequest($values = [
            'name' => fake()->name,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
        ]);

        $getRequest->validate();

        $this->assertEquals($values, $getRequest->validated());

        $postRequest = $this->buildRequest(post: $values);

        $postRequest->validate();

        $this->assertEquals($values, $postRequest->toArray());

        $this->assertNotEmpty($postRequest->toJson());
    }

    /**
     * @return void
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function testValidationFails(): void
    {
        $this->expectException(ValidationException::class);

        $failedRequest = $this->buildRequest([
            'name' => fake()->paragraph,
            'last_name' => null,
            'email' => 'foo',
        ]);

        $failedRequest->validate();
    }

    /**
     * @return void
     */
    public function testRequestValidatedWhenResolved() : void
    {
        $request = new Request([
            'name' => fake()->name,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
        ]);

        $this->app['request'] = $request;

        $getRequest = $this->buildRequest();

        $request = app($getRequest::class);

        $this->assertTrue($request->was_validated);
    }

    /**
     * @return void
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function testRequestAuthorization() : void
    {
        //by default the test request is authorized
        $authorizeRequest = $this->buildRequest();

        $this->assertTrue($authorizeRequest->authorize());
    }

    /**
     * @return void
     * @throws AuthorizationException
     * @throws ValidationException
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function testFailRequestAuthorization(): void
    {
        $authorizeRequest = $this->buildRequest();

        $authorizeRequest->authorize = false;

        $this->assertFalse($authorizeRequest->authorize);

        $this->expectException(AuthorizationException::class);

        $authorizeRequest->validate();
    }

    /**
     * @param array $get
     * @param array $post
     * @return SimpleRequest
     */
    protected function buildRequest(array $get = [], array $post = []) : SimpleRequest
    {
        $server = [
            'REQUEST_METHOD' => empty($post)? 'GET' : 'POST',
        ];

        return new class($get, $post, server: $server) extends SimpleRequest
        {
            /**
             * @var bool
             */
            public bool $was_validated = false;

            /**
             * @var bool
             */
            public bool $authorize = true;

            public function rules(): array
            {
                return array_merge(parent::rules(), [
                    'name' => ['required', 'max:100'],
                    'last_name' => ['required', 'max:100'],
                    'email' => ['sometimes', 'required', 'email'],
                ]);
            }

            public function validate(): void
            {
                $this->was_validated = true;

                parent::validate();
            }

            public function authorize(): bool
            {
                return parent::authorize() && $this->authorize;
            }
        };
    }
}
